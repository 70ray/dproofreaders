<?php

class UserProfileTest extends PHPUnit\Framework\TestCase
{
    private $TEST_USERNAME = "UserTest_php";
    private $USER;
    private $PROFILE_ID;
    private $NONEXISTENT_PROFILE_ID = 8675309;

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

    public function testEmptyConstructor()
    {
        $user_profile = new UserProfile();
        $this->assertTrue(!isset($user_profile->profilename));
    }

    public function testNonemptyConstructor()
    {
        $user_profile = new UserProfile($this->PROFILE_ID);
        $this->assertTrue(isset($user_profile->profilename));
    }

    /**
     * @expectedException NonexistentUserProfileException
     */
    public function testLoadNonexisting()
    {
        $user_profile = new UserProfile($this->NONEXISTENT_PROFILE_ID);
    }

    /**
     * @expectedException DomainException
     */
    public function testSetImmutable()
    {
        $user_profile = new UserProfile($this->PROFILE_ID);
        $user_profile->id = 42;
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testSaveNewUserProfileWithoutUserRef()
    {
        $user_profile = new UserProfile();
        $user_profile->save();
    }

    public function testSaveNewUserProfile()
    {
        $user_profile = new UserProfile();
        $user_profile->u_ref = $this->USER->u_id;
        $user_profile->profilename = "new profile";
        $user_profile->save();

        $verify_user_profile = new UserProfile($user_profile->id);
        $this->assertEquals(
            $user_profile->u_ref,
            $verify_user_profile->u_ref
        );
    }

    public function testSaveExistingUser()
    {
        $new_profilename = "new profile name";
        $user_profile = new UserProfile($this->PROFILE_ID);
        $user_profile->profilename = $new_profilename;
        $user_profile->save();

        $verify_user_profile = new UserProfile($user_profile->id);
        $this->assertEquals(
            $new_profilename,
            $verify_user_profile->profilename
        );
    }
}
