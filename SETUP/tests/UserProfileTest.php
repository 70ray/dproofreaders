<?php

class UserProfileTest extends PHPUnit\Framework\TestCase
{
    private $TEST_USERNAME = "UserTest_php";
    private $USER;
    private $PROFILE_ID;
    private $NONEXISTENT_PROFILE_ID = 8675309;
    private $TEST_PROFILENAME = "UserTestProfile";

    protected function setUp()
    {
        // Attempt to load our test user, if it exists don't create it
        try
        {
            $this->USER = new User($this->TEST_USERNAME);
        }
        catch(NonexistentUserException $exception)
        {
            $this->USER = new User();
            $this->USER->id = $this->TEST_USERNAME;
            $this->USER->username = $this->TEST_USERNAME;
            $this->USER->save();
        }

        // Create a user profile to use
        $sql = sprintf("
            INSERT INTO user_profiles
                SET u_ref = %d
        ", (int)$this->USER->u_id
        );

        $result = mysqli_query(DPDatabase::get_connection(), $sql);
        if(!$result)
            throw new Exception("Unable to create test user profile");

        $this->PROFILE_ID = mysqli_insert_id(DPDatabase::get_connection());
        $this->USER->u_profile = $this->PROFILE_ID;
        $this->USER->save();
    }

    protected function tearDown()
    {
        $sql = sprintf("
            DELETE FROM user_profiles
            WHERE u_ref = %d
        ", $this->USER->u_id);
        $result = mysqli_query(DPDatabase::get_connection(), $sql);

        $sql = "
            DELETE FROM users
            WHERE username = '$this->TEST_USERNAME'
        ";
        $result = mysqli_query(DPDatabase::get_connection(), $sql);
    }

    public function testGetUserProfile()
    {
        $user_profile =& UserProfile::get_user_profile($this->PROFILE_ID);
        $this->assertTrue(isset($user_profile->profilename));
    }

    /**
     * @expectedException DomainException
     */
    public function testSetImmutable()
    {
        $user_profile =& UserProfile::get_user_profile($this->PROFILE_ID);
        $user_profile->id = 42;
    }

    public function testGetProfileValue()
    {
        $ref = $this->USER->u_ref;
        $this->assertEquals(
            $ref,
            $this->USER->u_id
        );
    }

    public function testSetProfileValue()
    {
        $this->USER->profilename = $this->TEST_PROFILENAME;
        $this->assertEquals(
            $this->USER->profile->profilename,
            $this->TEST_PROFILENAME
        );
    }

    public function testGetCurrentProfile()
    {
        $current_profile = $this->USER->profile;
        $this->assertEquals(
            $current_profile->u_ref,
            $this->USER->u_id
        );
    }

    public function testSaveExistingUserProfile()
    {
        $new_profilename = "new profile name";
        $user_profile =& UserProfile::get_user_profile($this->PROFILE_ID);
        $user_profile->profilename = $new_profilename;
        $user_profile->save();

        $verify_user_profile =& UserProfile::get_user_profile($this->PROFILE_ID);
        $this->assertEquals(
            $new_profilename,
            $verify_user_profile->profilename
        );
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testGetNonexistentValue()
    {
        $nonex = $this->USER->nonexistent;
        $this->assertTrue(1);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testSetNonexistentValue()
    {
        $this->USER->nonexistent = 13;
        $this->assertTrue(1);
    }

    public function testGetProfilenames()
    {
        $this->USER->profilename = $this->TEST_PROFILENAME;
        $this->USER->save();
        $profilenames = UserProfile::get_profilenames_for_user($this->USER->u_id);
        $this->assertEquals(
            count($profilenames),
            1
        );
        $this->assertEquals(
            $this->TEST_PROFILENAME,
            $profilenames[$this->PROFILE_ID]
        );
    }

    public function testLinkNewProfile()
    {
        $this->USER->link_new_profile();
        $profilenames = UserProfile::get_profilenames_for_user($this->USER->u_id);
        $this->assertEquals(
            count($profilenames),
            2
        );
    }
}
