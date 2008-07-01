<?

// Translated by user 'Pierre' at pgdp.net, 2006-02-08
// Updated by user lvl, 2008-01-26

$relPath='../pinc/';
include($relPath.'site_vars.php');
include($relPath.'faq.inc');
include($relPath.'pg.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
$theme_args["css_data"] = "p.backtotop {text-align:right; font-size:75%;margin-right:-5%;}";

theme('Directives de Formatage','header',$theme_args);

$utf8_site=!strcasecmp($charset,"UTF-8");
?>

<!-- NOTE TO MAINTAINERS AND DEVELOPERS:

     There are now HTML comments interspersed in this document that are/will be
     used by a script that automagically slices out the Random Rule text for the
     database. It does this by copying:
       1) All text from one h_3 to the next h_3
        -OR-
       2) All text from h_3 to the END_RR comment line.

    This allows us to have "extra" information in the Guidelines, but leave it out
    in the Random Rule for purposes of clarity/brevity.

    If you are updating this document, the above should be kept in mind.
-->

<h1 align="center"><a name="top">Directives de Formatage</a></h1>

<h3 align="center">Version 1.9.e, le 17 janvier 2008 (traduction de la 1.9.e anglaise de juillet 2007)</h3>

<h4>Directives de Formatage <a href="document.php">en anglais</a> /
      Formatting Guidelines <a href="document.php">in English</a><br>
    Directives de Formatage <a href="formatting_guidelines_portuguese.php">en portugais</a> /
      Regras de Formata&ccedil;&atilde;o <a href="formatting_guidelines_portuguese.php">em Portugu&ecirc;s</a><br>
    Directives de Formatage <a href="formatting_guidelines_dutch.php">en n&eacute;erlandais</a> /
      Formatteer-Richtlijnen <a href="formatting_guidelines_dutch.php">in het Nederlands</a><br>
    Directives de Formatage <a href="formatting_guidelines_german.php">en allemand</a> /
      Formatierungsrichtlinien <a href="formatting_guidelines_german.php">auf Deutsch</a><br>
</h4>

<h4>Allez voir le <a href="../quiz/start.php?show_only=FQ">Quiz de formatage</a>.</h4>

<table border="0" cellspacing="0" width="100%" summary="Directives de formatage">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><font size="+2"><b>Table des mati&egrave;res</b></font></td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
    <ul>
      <li><a href="#prime">La r&egrave;gle principale</a></li>
      <li><a href="#summary">R&eacute;sum&eacute; des directives</a></li>
      <li><a href="#about">&Agrave; propos de ce document</a></li>
      <li><a href="#comments">Commentaires sur les projets</a></li>
      <li><a href="#forums">Forum/Discuter de ce Projet</a></li>
      <li><a href="#prev_pg">Corriger des erreurs sur les pages pr&eacute;c&eacute;dentes</a></li>
    </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">
      <ul>
        <li><font size="+1">Formatage de...</font></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#italics">Italiques</a></li>
        <li><a href="#bold">Texte gras</a></li>
        <li><a href="#underl">Texte soulign&eacute;</a></li>
        <li><a href="#spaced">T e x t e &nbsp; e s p a c &eacute; (gesperrt)</a></li>
        <li><a href="#font_ch">Changement de police</a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Petites capitales</span></a></li>
        <li><a href="#word_caps">Mots enti&egrave;rement en majuscules</a></li>
        <li><a href="#font_sz">Changement de taille de police</a></li>
        <li><a href="#extra_sp">Espaces exc&eacute;dentaires entre les mots</a></li>
        <li><a href="#supers">Texte en Exposant</a></li>
        <li><a href="#subscr">Texte en indice</a></li>
        <li><a href="#page_ref">R&eacute;f&eacute;rences aux pages "(Voir Pg. 123)"</a></li>
        <li><a href="#line_br">Retours &agrave; la ligne</a></li>
        <li><a href="#chap_head">En-t&ecirc;tes de chapitres</a></li>
        <li><a href="#sect_head">En-t&ecirc;tes de section</a></li>
        <li><a href="#maj_div">Autres divisions dans les textes</a></li>
        <li><a href="#para_space">Espacement et indentation des paragraphes</a></li>
        <li><a href="#extra_s">Espaces suppl&eacute;mentaires, lignes et ast&eacute;risques
            entre les paragraphes</a></li>
        <li><a href="#illust">Illustrations</a></li>
        <li><a href="#footnotes">Notes de fin et de notes de bas de page</a></li>
        <li><a href="#para_side">Commentaires en marge des paragraphes</a></li>
        <li><a href="#block_qt">Blocs de citations</a></li>
        <li><a href="#mult_col">Colonnes multiples</a></li>
        <li><a href="#lists">Listes de choses</a></li>
        <li><a href="#tables">Tableaux</a></li>
        <li><a href="#poetry">Po&eacute;sie/&Eacute;pigrammes</a></li>
        <li><a href="#line_no">Num&eacute;ros de ligne</a></li>
        <li><a href="#letter">Lettres (courrier)</a></li>
        <li><a href="#blank_pg">Page blanche</a></li>
        <li><a href="#title_pg">Page de titre/fin</a></li>
        <li><a href="#toc">Table des mati&egrave;res</a></li>
        <li><a href="#bk_index">Index</a></li>
        <li><a href="#play_n">Th&eacute;&acirc;tre</a></li>
        <li><a href="#anything">Tous autres points n&eacute;cessitant un traitement particulier, ou dont vous
            n'&ecirc;tes pas s&ucirc;r</a></li>
        <li><a href="#prev_notes">Notes des correcteurs pr&eacute;c&eacute;dents</a></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">
    <ul>
      <li><font size="+1">Guides sp&eacute;cifiques pour livres particuliers</font></li>
    </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#sp_ency">Encyclop&eacute;dies</a></li>
        <li><a href="#sp_poet">Po&eacute;sie</a></li>
        <li><a href="#sp_chem">Chimie</a>   [&agrave; compl&eacute;ter.]</li>
        <li><a href="#sp_math">Math&eacute;matiques</a> [&agrave; compl&eacute;ter.]</li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">
    <ul>
      <li><font size="+1">Probl&egrave;mes courants</font></li>
    </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#bad_image">Mauvaises images</a></li>
        <li><a href="#bad_text">Image ne correspondant pas au texte</a></li>
        <li><a href="#round1">Erreurs des correcteurs pr&eacute;c&eacute;dents</a></li>
        <li><a href="#p_errors">Erreurs d'impressions et d'orthographe</a></li>
        <li><a href="#f_errors">Erreurs factuelles dans les textes</a></li>
        <li><a href="#uncertain">Points incertains</a></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver">&nbsp;</td>
  </tr>
 </tbody>
</table>

<h3><a name="prime">La r&egrave;gle principale</a></h3>
<p><em>"Ne changez pas ce que l'auteur a &eacute;crit!"</em>
</p>
<p>Le livre &eacute;lectronique final vu par un lecteur, potentiellement plusieurs ann&eacute;es dans le
   futur, doit transmettre l'intention de l'auteur de mani&egrave;re exacte.
   Si l'auteur &eacute;crit des mots d'une mani&egrave;re &eacute;trange, laissez-les.
   Si l'auteur &eacute;crit des choses choquantes, racistes ou partiales, laissez-les
   telles quelles. Si l'auteur semble mettre des italiques, des mots en gras ou des notes de bas de
   page tous les trois mots, gardez les italiques, les mots en gras et les notes de
   bas de page. Voir toutefois la section <a href="#p_errors">Erreurs d'impression</a>
   pour les fautes &eacute;videntes de l'imprimeur.
</p>
<p>Par contre, nous changeons des choses mineures qui n'affectent pas le sens de
   ce que l'auteur a &eacute;crit. Nous rejoignons les mots s&eacute;par&eacute;s par
   un retour &agrave; la ligne. (voir <a href="#eol_hyphen">Traits d'union en fin de lignes</a>)
   Ces changements nous permettent d'avoir des livres <em>format&eacute;s d'une fa&ccedil;on
   homog&egrave;ne</em>. Nous suivons des r&egrave;gles de relecture pour avoir ce
   r&eacute;sultat. Lisez attentivement le reste de ces R&egrave;gles en gardant ce concept &agrave; l'esprit.
</p>
<p>Pour aider le prochain formateur et le post-processeur, nous gardons aussi
   les <a href="#line_br">retours &agrave; la ligne</a>. Il est ainsi facile de
   comparer les lignes du texte corrig&eacute; et les lignes de l'image.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>

<table width="100%" border="0" cellspacing="0" summary="R&eacute;sum&eacute; des directives">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>


<h3><a name="summary">R&eacute;sum&eacute; des directives</a></h3>
<p>Le <a href="formatting_summary.pdf">R&eacute;sum&eacute; des directives</a> (en anglais)
   est un court document imprimable de 2 pages (.pdf) qui r&eacute;sume les
   points principaux de ces directives, et qui donne des exemples de corrections.
   Les formateurs d&eacute;butants sont encourag&eacute;s &agrave; imprimer ce document et &agrave; le garder
   &agrave; port&eacute;e de main quand ils formatent.
</p>
<p>Vous aurez besoin d'un lecteur de fichiers .pdf. Vous pouvez en t&eacute;l&eacute;charger
   un gratuitement chez Adobe&reg; <a href="http://www.adobe.com/products/acrobat/readstep2.html">ici</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="about">&Agrave; propos de ce document</a></h3>
<p>Ce document a pour but de r&eacute;duire les diff&eacute;rences de formatage entre les
   travaux des diff&eacute;rents correcteurs qui ont travaill&eacute; sur un m&ecirc;me livre, de
   mani&egrave;re &agrave; ce que nous formations tous <em>de la m&ecirc;me mani&egrave;re.</em>
   Cela rend le travail plus facile aux post-processeurs.
</p>
<p><i>Ce document n'est en aucune mani&egrave;re cens&eacute; &ecirc;tre un recueil 
   de r&egrave;gles &eacute;ditoriales ou typographiques.</i>
</p>
<p>Nous avons inclus dans ce document tous les points de formatage et de correction 
   qui ont suscit&eacute; des questions de la part des nouveaux utilisateurs. S'il
   manque des points, ou que vous consid&eacute;rez que des points manquent, ou que des
   points devraient &ecirc;tre d&eacute;crits de mani&egrave;re diff&eacute;rente ou si quelque chose est
   vague, merci de nous le faire savoir.
</p>
<p>Ce document est un travail en &eacute;volution permanente. Aidez-nous &agrave; progresser en nous
   envoyant vos suggestions de changements sur le forum Documentation dans
   <a href="<? echo $Guideline_discussion_URL; ?>">ce thread</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="comments">Commentaires des projets</a></h3>

<p>Dans la page du projet depuis laquelle vous commencez &agrave; formater des pages,
   il y a une section "project comments" (Commentaires du projet) qui contient des informations
   sp&eacute;cifiques &agrave; ce projet (livre). <b>Lisez celles-ci avant de commencer &agrave;
   formater des pages!</b> Si le responsable de projet veut que vous formatiez
   quelque chose dans ce livre autrement que ce qui est dit dans ces directives, ce
   sera indiqu&eacute; l&agrave;. Les instructions dans les &ldquo;Commentaires du projet&rdquo; <em>supplantent</em>
   les r&egrave;gles dans ces directives, donc suivez-les. C'est aussi &agrave; cet endroit
   que le responsable de projet vous donne des informations int&eacute;ressantes &agrave; propos
   des livres, comme leur provenance, etc.
</p>
<p><em>Lisez aussi le forum de discussion du projet</em>: Le chef de projet y
   clarifie des points portant sp&eacute;cifiquement sur le projet. Cette discussion est
   souvent utilis&eacute;e par les relecteurs pour signaler aux autres relecteurs les
   probl&egrave;mes r&eacute;currents dans le projet, et la meilleure fa&ccedil;on de les r&eacute;soudre.
</p>
<p>Sur la page du projet, le lien 'Images, Pages Proofread, &amp; Differences'
   permet de voir comment les autres relecteurs ont chang&eacute; le texte.
   <a href="<? echo $Using_project_details_URL ?>">Ce fil de discussion</a>
   discute les diff&eacute;rentes fa&ccedil;on d'utiliser cette information.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="forums">Forum/Discuter de ce Projet</a></h3>
<p>Dans la page du projet dans laquelle vous commencez &agrave; formater des pages,
   sur la ligne &ldquo;Forum&rdquo;, il y a un lien indiquant &ldquo;Discuss this Project&rdquo; 
   (Discuter de ce projet, si la discussion a d&eacute;j&agrave; commenc&eacute;)
   ou bien &ldquo;Start a discussion on this Project&rdquo;
   (D&eacute;marrer une discussion sur le projet, sinon). 
   Cliquer sur ce lien vous am&egrave;nera &agrave; un "thread" de forum d&eacute;di&eacute; sp&eacute;cifiquement &agrave; ce projet.
   C'est l'endroit o&ugrave; poser des questions &agrave; propos de ce livre,
   informer le responsable de projet &agrave; propos de probl&egrave;mes, etc. L'utilisation de
   ce forum est la mani&egrave;re recommand&eacute;e pour discuter avec le responsable de projet
   et les autres correcteurs qui travaillent sur ce livre.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="prev_pg">Corriger des erreurs sur des pages pr&eacute;c&eacute;dentes</a></h3>
<p>Quand vous s&eacute;lectionnez un projet pour travailler, vous 
   &ecirc;tes initialement dans la <a href="#comments">page du projet</a>. Cette page contient des
   liens vers les pages que vous avez format&eacute;es r&eacute;cemment (si vous n'avez pas
   encore format&eacute; de pages, alors aucun lien ne sera affich&eacute;).
</p>
<p>Les pages list&eacute;es sous "DONE" et "IN PROGRESS" sont disponibles pour que vous
   puissiez corriger ou terminer votre travail de relecture. Cliquez sur le lien
   vers la page. Ainsi, si vous voyez que vous avez fait une erreur sur une page,
   vous pouvez cliquer sur cette page, et la rouvrir pour corriger l'erreur.
</p>
<p>Il est &eacute;galement possible depuis la <a href="#comments">page du projet</a> d'utiliser les liens
   "Images, Pages Proofread, &amp; Differences" ou l'option
   "Just my pages". Ces pages pr&eacute;sentent un lien "Edit" sur
   toutes les pages sur lesquelles vous avez travaill&eacute; durant
   ce round. Il est encore temps de les corriger.
</p>
<p>Pour plus de d&eacute;tails, voyez <a href="prooffacehelp.php?i_type=0">Aide sur l'interface
   standard</a> ou bien <a href="prooffacehelp.php?i_type=1">Aide sur l'interface
   avanc&eacute;e</a>, selon l'interface que vous utilisez.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>

<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Page de titre">
  <tbody>
    <tr>
      <td bgcolor="silver"><font size="+2">Formatage de...</font></td>
    </tr>
  </tbody>
</table>


<h3><a name="italics">Italiques</a></h3>
<p>Le texte en <i>italique</i> doit avoir <tt>&lt;i&gt;</tt> ins&eacute;r&eacute; avant et
   <tt>&lt;/i&gt;</tt> ins&eacute;r&eacute; &agrave; la fin de l'italique.
   (remarquez le &ldquo;<tt>/</tt>&rdquo; dans le symbole de fin).
</p>
<p>La ponctuation va <b>hors</b> de l'italique, &agrave; moins que le signe de ponctuation ne soit
   dans une phrase ou une section enti&egrave;re qui est en italique,
   ou que ce signe fasse partie d'une phrase, titre ou abr&eacute;viation qui
   est en italique.
</p>
<p>Par exemple, le <tt>.</tt> qui signale l'abr&eacute;viation dans le titre d'un journal
   comme <i>Phil. Trans.</i> est entre les marques d'italiques. D'o&ugrave;&nbsp;:
   <tt>&lt;i&gt;Phil. Trans.&lt;/i&gt;</tt>. 
   <!-- the following sentence is not present in the english original -->
   Voyez l'image de la section
   <a href="#illust">Illustration</a> pour un exemple de la mani&egrave;re de faire les italiques.
</p>
<p>Certaines polices, en particulier les plus vieilles utilisaient les m&ecirc;mes
   symboles pour les nombres en italique et non italique. Donc, pour les dates et
   phrases similaires, marquez la phrase enti&egrave;re en italique plut&ocirc;t que de
   marquer les mots en italique et les nombres en non italique.
</p>
<p>Si le texte en italique est une s&eacute;rie/liste de mots ou de noms, mettez chacun
   d'eux en italiques (et pas la liste dans son ensemble).
</p>
<!-- END RR -->

<p><b>Exemples</b>&mdash;Italiques:
</p>

<table width="100%" align="center" border="1" cellpadding="4" 
cellspacing="0" summary="Italique">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Texte original:</th>
      <th valign="top" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
    </tr>
    <tr>
      <td valign="top"><i>Enacted </i>4<i> July, </i>1776 </td>
      <td valign="top"><tt>&lt;i&gt;Enacted 4 July, 1776&lt;/i&gt;</tt> </td>
    </tr>
    <tr>
      <td valign="top"><i>God knows what she saw in me!</i> I spoke<br> in such an affected manner.</td>
      <td valign="top"><tt>&lt;i&gt;God knows what she saw in me!&lt;/i&gt; I spoke<br> in such an affected manner.</tt></td>
    </tr>
    <tr>
      <td valign="top">As in many other of these <i>Studies</i>, and</td>
      <td valign="top"><tt>As in many other of these &lt;i&gt;Studies&lt;/i&gt;, and</tt></td>
    </tr>
    <tr>
      <td valign="top">(<i>Psychological Review</i>, 1898, p. 160)</td>
      <td valign="top"><tt>(&lt;i&gt;Psychological Review&lt;/i&gt;, 1898, p. 160)</tt></td>
    </tr>
    <tr>
      <td valign="top">L. Robinson, art. "<i>Ticklishness</i>,"</td>
      <td valign="top"><tt>L. Robinson, art. "&lt;i&gt;Ticklishness&lt;/i&gt;,"</tt></td>
    </tr>
    <tr>
      <td valign="top" align="right"><i>December</i> 3, <i>morning</i>.<br>
                     1323 Picadilly Circus</td>
      <td valign="top"><tt>/*<br>
         &lt;i&gt;December 3, morning.&lt;/i&gt;<br>
         1323 Picadilly Circus<br>
         */</tt></td>
    </tr>
    <tr>
      <td valign="top">
      Volunteers may be tickled pink to read<br>
      <i>Ticklishness</i>, <i>Tickling and Laughter</i>,<br>
      <i>Remarks on Tickling and Laughter</i><br>
      and <i>Ticklishness, Laughter and Humour</i>.
      </td>
      <td valign="top">
      <tt>Volunteers may be tickled pink to read<br>
      &lt;i&gt;Ticklishness&lt;/i&gt;, &lt;i&gt;Tickling and Laughter&lt;/i&gt;,<br>
      &lt;i&gt;Remarks on Tickling and Laughter&lt;/i&gt;<br>
      and &lt;i&gt;Ticklishness, Laughter and Humour&lt;/i&gt;.</tt>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="bold">Texte en gras</a></h3>
<p>Le <b>texte en gras</b> doit &ecirc;tre marqu&eacute; par <tt>&lt;b&gt;</tt> avant et
   <tt>&lt;/b&gt;</tt> apr&egrave;s.
   (remarquez le &ldquo;<tt>/</tt>&rdquo; dans la marque de fin).
</p>
<p>Les signes de ponctuation doivent &ecirc;tre <b>hors</b> des marques de "gras", &agrave; moins
   que ces signes ne soient dans une phrase ou une partie de phrase enti&egrave;rement en
   gras.
</p>
<p>Voyez l'exemple de la section <a href="#page_hf">En-t&ecirc;tes et bas de page</a>.
</p>
<p>Certains chefs de projet peuvent sp&eacute;cifier dans les <a href="#comments">commentaires
   de projet</a> que le texte gras doit &ecirc;tre rendu tout en majuscules.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="underl">Texte soulign&eacute;</a></h3>
<p>Marquez le <u>texte soulign&eacute;</u> comme &eacute;tant de l'<a href="#italics">Italique</a>,
   avec <tt>&lt;i&gt;</tt> et <tt>&lt;/i&gt;</tt>, &agrave; moins que les <a href="#comments">Commentaires
   de projet</a> sp&eacute;cifient l'utilisation de <tt>&lt;u&gt;</tt> et <tt>&lt;/u&gt;</tt>
   pour ce livre.
</p>
<p>On avait souvent recours au texte soulign&eacute; &agrave; la place de l'italique quand l'&eacute;diteur
   &eacute;tait incapable de imprimer en italique, par exemple pour un document tap&eacute; &agrave; la machine.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="spaced">T e x t e&nbsp;&nbsp; e s p a c &eacute; (gesperrt)</a></h3>
<p>Formatez le &nbsp;T e x t e&nbsp;&nbsp; e s p a c &eacute;&nbsp; avec les marques <tt>&lt;g&gt;</tt> avant et
   <tt>&lt;/g&gt;</tt> apr&egrave;s.
   (remarquez le &ldquo;<tt>/</tt>&rdquo; dans la marque de fin).
   Enlevez les espaces exc&eacute;dentaires. 
</p>
<p>La ponctuation va <b>en dehors</b> des marques, sauf si une phrase ou une section enti&egrave;re
   est ainsi espac&eacute;e, ou si la ponctuation fait partie de la partie de texte espac&eacute;e.
</p>
<p>Cette technique &eacute;tait utilis&eacute;e pour mettre l'accent sur certains passages 
   sur certains livres anciens, principalement en allemand.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="font_ch">Changement de police</a></h3>
<p>Formatez le changement de police &agrave; l'int&eacute;rieur d'un paragraphe en mettant
   <tt>&lt;f&gt;</tt> avant et <tt>&lt;/f&gt;</tt> apr&egrave;s le passage. 
   (remarquez le &ldquo;<tt>/</tt>&rdquo; dans la marque de fin).
   Utilisez ces marques pour noter la pr&eacute;sence de toute police sp&eacute;ciale ou de 
   typographie particuli&egrave;re, <b>sauf</b> pour le gras, l'italique, les petites
   capitales, et le texte espac&eacute;, qui ont leurs marques d&eacute;di&eacute;es.
</p>
<p>Parmi les usages possibles de ce marquage, on trouve:
</p>
<ul compact>
  <li>antiqua (une sorte de police en roman) au milieu de texte en fraktur</li>
  <li>caract&egrave;res gothiques au milieu d'un texte en polices normales</li>
  <li>une police plus grande ou plus petite, seulement si c'est un passage au milieu
    d'un paragraphe en police normale (pour un paragraphe entier dans une police ou
    taille diff&eacute;rente, voir la section <a href="#block_qt">bloc de citation</a>)</li>
  <li>une police droite au milieu d'un paragraphe en italique</li>
</ul>
<p>Les usages particuliers de ce marquage dans un projet seront g&eacute;n&eacute;ralement pr&eacute;cis&eacute;s
   dans les <a href="#comments">commentaires du projet</a>. Les formateurs devraient
   signaler dans le <a href="#forums">forum du projet</a> si l'usage de ce marquage
   semble n&eacute;cessaire et n'a pas encore &eacute;t&eacute; indiqu&eacute;.
</p>
<p>La ponctuation va <b>en dehors</b> des marques, sauf si une phrase ou une section enti&egrave;re
   est ainsi imprim&eacute;e dans une police diff&eacute;rente, ou si la ponctuation fait partie 
   de la partie de texte dans la police diff&eacute;rente.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="small_caps">Petites capitales</a></h3>
<p>Le marquage est diff&eacute;rent pour les <span style="font-variant: small-caps;">Petites Capitales Altern&eacute;es</span>
   avec des grandes capitales, et pour les mots <span style="font-variant: small-caps;">uniquement en petites capitales</span>:
</p>
<p>Corrigez les mots en <span style="font-variant: small-caps;">Petites Capitales Altern&eacute;es</span>
   en faisant alterner minuscules et majuscules et entourez le texte par les marques
   <tt>&lt;sc&gt;</tt> et <tt>&lt;/sc&gt;</tt>. <br>
&nbsp;&nbsp;&nbsp;&nbsp;Exemple:
   <span style="font-variant: small-caps;">This is Small Caps</span> <br>
&nbsp;&nbsp;&nbsp;&nbsp;devient:
   <tt>&lt;sc&gt;This is Small Caps&lt;/sc&gt;</tt>.
</p>

<p>Mais si les mots sont imprim&eacute;s <span style="font-variant: small-caps;">enti&egrave;rement
   en petites capitales</span>, alors &eacute;crivez-les en CAPITALES, et entourez-les
   des marques <tt>&lt;sc&gt;</tt> et <tt>&lt;/sc&gt;</tt>.
   <br>
&nbsp;&nbsp;&nbsp;&nbsp;Exemple:
   You cannot be serious about
   <span style="font-variant: small-caps;">aardvarks</span>!<br>
&nbsp;&nbsp;&nbsp;&nbsp;devient:
   <tt>You cannot be serious about
   &lt;sc&gt;AARDVARKS&lt;/sc&gt;!</tt> <br>
</p>

<p>Si un mot est en capitales dans un titre (un en-t&ecirc;te de chapitre, ou de section), laissez-le
   en capitales, sans marques <tt>&lt;sc&gt;</tt> et <tt>&lt;/sc&gt;</tt>. Si le premier mot
   d'un chapitre est en petites capitales, alors changez-le en majuscules et minuscules, 
   sans marques de petites capitales.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="word_caps">Mots entiers en majuscules</a></h3>
<p>Si un mot ou groupe de mots dans un texte est imprim&eacute; enti&egrave;rement en
   majuscules, laissez-les tels qu'ils sont dans votre copie de travail.
</p>
<p>Une exception &agrave; cette r&egrave;gle est le <a href="#chap_head">premier
   mot d'un chapitre ou d'un paragraphe</a>: certains livres anciens mettaient le
   premier mot de chaque chapitre en majuscule; ce doit &ecirc;tre chang&eacute; en un mot
   normal (premi&egrave;re lettre en majuscule, le reste en minuscule). Donc "IL &eacute;tait une
   fois" devient "<tt>Il &eacute;tait une fois</tt>".
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="font_sz">Changement de taille de police</a></h3>
<p>Ne faites rien pour indiquer un changement de taille de police.
</p>
<p>L'exception &agrave; ceci est lorsque la taille de la police change pour indiquer un
   <a href="#block_qt">bloc de citation</a>, ou quand le changement de taille
   a lieu au milieu d'un paragraphe ou d'une ligne du texte 
   (voir <a href="#font_ch">Changement de police</a>).
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="extra_sp">Espaces exc&eacute;dentaires entre les mots</a></h3>
<p>Des espaces suppl&eacute;mentaires ou des caract&egrave;res de tabulation 
   entre les mots sont une erreur d'OCR courante. Il
   n'est pas n&eacute;cessaire de supprimer ces espaces &eacute;tant donn&eacute;
   qu'il est facile de le faire automatiquement lors du post-processing.
</p>
<p>Mais les espaces exc&eacute;dentaires autour de la ponctuation, des tirets, etc.
   doivent &ecirc;tre supprim&eacute;s car il est difficile de faire cela automatiquement.
</p>
<p>Par exemple, dans <tt>A horse&nbsp;;&nbsp;&nbsp;my kingdom for a horse.</tt> il faut supprimer
   l'espace avant le point virgule. Mais les deux espaces apr&egrave;s le point
   virgule ne posent pas de probl&egrave;me&nbsp;: vous n'&ecirc;tes pas oblig&eacute;s
   d'en supprimer un.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="supers">Exposants</a></h3>
<p>Les vieux livres abr&eacute;geaient souvent les mots en contractions, et les
   imprimaient en exposant, par exemple:<br>
   &nbsp;&nbsp;&nbsp;&nbsp;Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.<br>
   Formatez ceci en ins&eacute;rez un chapeau (<tt>^</tt>) suivi par le texte en exposant, comme suit:<br>
   &nbsp;&nbsp;&nbsp;&nbsp;<tt>Gen^rl Washington defeated L^d Cornwall's army.</tt>
</p>
<p>Dans les ouvrages scientifiques et techniques utilisez le "chapeau" <tt>^</tt> et mettez
   le texte en exposant entre accolades <tt>{</tt> et <tt>}</tt>. Mettez toujours le texte
   en exposant entre accolades, m&ecirc;me si ce texte ne fait qu'un caract&egrave;re.
   <br>Ainsi:
   <br>&nbsp;&nbsp;&nbsp;&nbsp;... up to x<sup>n-1</sup> elements in the array.
   <br>donne
   <br>&nbsp;&nbsp;&nbsp;&nbsp;<tt>... up to x^{n-1} elements in the array.<br></tt>
</p>
<p>Si le chef de projet dit de faire autrement dans les <a href="#comments">commentaires
   de projet</a>, suivez ses instructions.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="subscr">Texte en Indice</a></h3>
<p>On trouve la notation "indice" dans des ouvrages scientifiques, rarement
   ailleurs. Indiquez l'indice en mettant un signe "soulign&eacute;" <tt>_</tt> devant et
   en entourant le texte en indice avec des accolades <tt>{</tt> et <tt>}</tt>.
   <br>Par exemple:
   <br>&nbsp;&nbsp;&nbsp;&nbsp;H<sub>2</sub>O.
   <br>donne
   <br>&nbsp;&nbsp;&nbsp;&nbsp;<tt>H_{2}O.<br></tt>
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="page_ref">R&eacute;f&eacute;rences aux pages "cf. p. 123"</a></h3>
<p>Laissez ces r&eacute;f&eacute;rences telles qu'elles sont dans le texte, &agrave; moins que le
   responsable de projet ne prescrive autre chose dans les <a href="#comments">Commentaires
   de Projet</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="line_br">Retours &agrave; la ligne</a></h3>
<p><b>Laissez tous les retours &agrave; la ligne</b> de mani&egrave;re &agrave; ce que le
   formateur suivant et le post-processeur puissent comparer les textes facilement. 
   Faites surtout attention aux
   cas particulier des <a href="#eol_hyphen">mots coup&eacute;s</a>, ou des
   <a href="#em_dashes">tirets</a>. Si la personne qui est pass&eacute; avant vous
   a supprim&eacute; les retours &agrave; la ligne, remettez-les, pour que les lignes
   correspondent &agrave; l'image.
</p>
<p>Les lignes vierges qui ne se trouvent pas dans l'image doivent &ecirc;tre
   supprim&eacute;es, sauf celles qui ont &eacute;t&eacute; ins&eacute;r&eacute;es
   intentionnellement pour le formatage. Mais des lignes vierges en bas de la page
   ne posent pas de probl&egrave;mes, ces derni&egrave;res pouvant &ecirc;tre
   supprim&eacute;s facilement en post-processing.
</p>
<!-- END RR -->
<!-- We should have an example right here for this. -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="chap_head">En-t&ecirc;tes de chapitres</a></h3>
<p>Laissez les en-t&ecirc;tes de chapitres dans le texte tels qu'ils sont imprim&eacute;s.
</p>
<p>Un en-t&ecirc;te de chapitre peut commencer plus bas sur la page qu'un <a href="#page_hf">en-t&ecirc;te de
   page</a> et n'a pas de num&eacute;ro de page sur la m&ecirc;me ligne. Les en-t&ecirc;tes de
   chapitres sont souvent imprim&eacute;s enti&egrave;rement en majuscules, si c'est le cas,
   laissez-les tels quels. Les en-t&ecirc;tes de chapitre sont g&eacute;n&eacute;ralement imprim&eacute;s dans
   une police diff&eacute;rente ou plus grande, et peuvent sembler &ecirc;tre en gras ou bien espac&eacute;s,
   mais nous ne notons pas la pr&eacute;sence de gras, fontes diff&eacute;rentes ou texte espac&eacute; 
   dans ces titres.
   En revanche nous utilisons le marquage de l'italique et des petites capitales si c'est
   pr&eacute;sent dans l'en-t&ecirc;te.
</p>
<p>Introduisez 4 lignes vierges avant le &ldquo;CHAPITRE XXX&rdquo; (ins&eacute;rez ces lignes
   blanches m&ecirc;me si le chapitre d&eacute;marre sur une nouvelle page; il n'y a pas de
   pages sur un livre &eacute;lectronique, donc les lignes blanches sont n&eacute;cessaires).
   Laissez ensuite une ligne blanche entre chaque partie de l'en-t&ecirc;te du chapitre,
   comme la description du chapitre, ou une citation en ouverture, etc. et laissez
   2 lignes vierges apr&egrave;s, entre l'en-t&ecirc;te et le texte du chapitre.
</p>
<p>Les vieux livres impriment souvent le premier mot de chaque chapitre
   enti&egrave;rement en majuscule; changez ces derniers en mots normaux (premi&egrave;re lettre
   seule en majuscule).
</p>
<p>Faites attention &agrave; un guillemet (&nbsp;"&nbsp;) au d&eacute;but du premier paragraphe,
   que certains &eacute;diteurs n'incluaient pas ou que l'OCR ignore &agrave; cause de la
   grande majuscule dans l'original. Si l'auteur commence le paragraphe avec un
   dialogue, ins&eacute;rez le guillemet.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Chapitres">
 <tbody>
    <tr><th align="left" bgcolor="cornsilk">Exemple d'image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="chap1.png" alt=""
          width="500" height="725"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
    <tr>
      <td width="100%" valign="top">
    <tt>
    GREEN FANCY<br>
    <br>
    <br>
    <br>
    <br>
    CHAPTER I<br>
    <br>
    THE FIRST WAYFARER AND THE SECOND WAYFARER<br>
    MEET AND PART ON THE HIGHWAY<br>
    <br>
    <br>
    A solitary figure trudged along the narrow<br>
    road that wound its serpentinous way<br>
    through the dismal, forbidding depths of<br>
    the forest: a man who, though weary and footsore,<br>
    lagged not in his swift, resolute advance. Night<br>
    was coming on, and with it the no uncertain prospects<br>
    of storm. Through the foliage that overhung<br>
    the wretched road, his ever-lifting and apprehensive<br>
    eye caught sight of the thunder-black, low-lying<br>
    clouds that swept over the mountain and bore<br>
    down upon the green, whistling tops of the trees.<br>
    <br>
    At a cross-road below he had encountered a small<br>
    girl driving homeward the cows. She was afraid<br>
    of the big, strange man with the bundle on his back<br>
    and the stout walking stick in his hand: to her a<br>
    remarkable creature who wore "knee pants" and<br>
    stockings like a boy on Sunday, and hob-nail shoes,<br>
    and a funny coat with "pleats" and a belt, and a<br>
    green hat with a feather sticking up from the band.
    </tt>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="En-t&ecirc;te et pied de page">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Exemple d'image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top">
      <img src="foot.png" alt="" width="500" height="850"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
    <tr>
      <td width="100%" valign="top">
    <tt>/#<br>In the United States?[A] In a railroad? In a mining company?<br>
    In a bank? In a church? In a college?<br>
    <br>
    Write a list of all the corporations that you know or have<br>
    ever heard of, grouping them under the heads &lt;i&gt;public&lt;/i&gt; and &lt;i&gt;private&lt;/i&gt;.<br>
    <br>
    How could a pastor collect his salary if the church should<br>
    refuse to pay it?<br>
    <br>
    Could a bank buy a piece of ground "on speculation?" To<br>
    build its banking-house on? Could a county lend money if it<br>
    had a surplus? State the general powers of a corporation.<br>
    Some of the special powers of a bank. Of a city.<br>
    <br>
    A portion of a man's farm is taken for a highway, and he is<br>
    paid damages; to whom does said land belong? The road intersects<br>
    the farm, and crossing the road is a brook containing<br>
    trout, which have been put there and cared for by the farmer;<br>
    may a boy sit on the public bridge and catch trout from that<br>
    brook? If the road should be abandoned or lifted, to whom<br>
    would the use of the land go?<br>#/<br>
    <br>
    <br>
    <br>
    <br>
    CHAPTER XXXV.<br>
    <br>
    &lt;sc&gt;Commercial Paper.&lt;/sc&gt;<br>
    <br>
    <br>
    &lt;b&gt;Kinds and Uses.&lt;/b&gt;--If a man wishes to buy some commodity<br>
    from another but has not the money to pay for<br>
    it, he may secure what he wants by giving his written<br>
    promise to pay at some future time. This written<br>
    promise, or &lt;i&gt;note&lt;/i&gt;, the seller prefers to an oral promise<br>
    for several reasons, only two of which need be mentioned<br>
    here: first, because it is &lt;i&gt;prima facie&lt;/i&gt; evidence of<br>
    the debt; and, second, because it may be more easily<br>
    transferred or handed over to some one else.<br>
    <br>
    If J. M. Johnson, of Saint Paul, owes C. M. Jones,<br>
    of Chicago, a hundred dollars, and Nelson Blake, of<br>
    Chicago, owes J. M. Johnson a hundred dollars, it is<br>
    plain that the risk, expense, time and trouble of sending<br>
    the money to and from Chicago may be avoided,<br>
    <br>
    [Footnote A: The United States: "Its charter, the constitution. * * * Its flag the<br>
    symbol of its power; its seal, of its authority."--Dole.]
    </tt>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="sect_head">En-t&ecirc;tes de section</a></h3>
<p>Dans certains livres, les chapitres sont divis&eacute;s en sections. Laissez les
   en-t&ecirc;tes de section comme ils sont imprim&eacute;s. Laissez deux lignes blanches avant
   cet en-t&ecirc;te, et une apr&egrave;s (&agrave; moins que le chef de projet en ait d&eacute;cid&eacute;
   autrement). Si vous ne savez pas si un en-t&ecirc;te d&eacute;marre un chapitre ou une
   section, demandez dans la discussion de forum d&eacute;di&eacute;e au projet, en pr&eacute;cisant le
   num&eacute;ro de page. 
   Les en-t&ecirc;tes de sections sont souvent imprim&eacute;s dans une police diff&eacute;rente, ou plus
   grande, et peuvent &ecirc;tre en gras ou espac&eacute;s, mais nous ne notons pas la pr&eacute;sence de 
   gras, fontes diff&eacute;rentes ou texte espac&eacute; dans ces titres.
   En revanche nous utilisons le marquage de l'italique et des petites capitales 
   si c'est pr&eacute;sent dans l'en-t&ecirc;te.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="maj_div">Autres divisions dans les textes</a></h3>
<p>Les autres grandes divisions des textes (Pr&eacute;face, Avant-propos, Introduction,
   Prologue, &Eacute;pilogue, Annexe, R&eacute;f&eacute;rences, Conclusion, Glossaire, R&eacute;sum&eacute;,
   Remerciements, Bibliographie, etc.) seront trait&eacute;s comme des en-t&ecirc;tes de
   chapitre. Quatre lignes blanches avant l'en-t&ecirc;te, et deux avant le d&eacute;but du
   texte.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="para_space">Espacement/Indentation des paragraphes</a></h3>
<p>Mettez une ligne blanche avant tout d&eacute;but de paragraphe, m&ecirc;me si ce
   paragraphe d&eacute;marre en haut d'une page. N'indentez pas le d&eacute;but des paragraphes
   (Mais si les paragraphes sont d&eacute;j&agrave; indent&eacute;s, ne prenez pas la peine
   d'enlever les espaces en trop&mdash;cela peut &ecirc;tre fait facilement &agrave; la phase de
   post-processing).
</p>
<p>Voyez l'exemple de la section <a href="#chap_head">En-t&ecirc;tes de
   chapitres</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="extra_s">Espace suppl&eacute;mentaire, ast&eacute;risques, lignes entre les
   paragraphes</a></h3>
<p>La plupart des livres commencent un paragraphe imm&eacute;diatement apr&egrave;s la fin du
   paragraphe pr&eacute;c&eacute;dent. Mais il peut arriver que deux paragraphes soient
   s&eacute;par&eacute;s par une rang&eacute;e d'&eacute;toiles, une ligne
   de tirets, ou autres caract&egrave;res, une ligne droite simple ou d&eacute;cor&eacute;e
   ou m&ecirc;me simplement une ligne blanche.
</p>
<p>Ceci est utilis&eacute; pour exprimer une pause, une parenth&egrave;se de pens&eacute;e, un changement de
   sc&egrave;ne, l'&eacute;coulement du temps ou pour cr&eacute;er un peu de suspense. Ceci
   refl&egrave;te l'intention de l'auteur, donc nous pr&eacute;servons ces lignes en
   ins&eacute;rant une ligne vierge suivie de <tt>&lt;tb&gt;</tt>, puis une autre ligne
   vierge.
</p>
<p>Les responsables de projet vous demanderont peut-&ecirc;tre de faire la
   diff&eacute;rence entre les diff&eacute;rents types de pause, pour garder
   plus d'information. Par exemple, ils vous demanderont de noter une ligne d'&eacute;toiles
   par <tt>&lt;tb stars&gt;</tt> et une ligne blanche par <tt>&lt;tb&gt;</tt>.
   Suivez attentivement les directives de projet. Et ne confondez pas les directives
   des diff&eacute;rents projets!
</p>
<p>Parfois, les imprimeurs ajoutent une ligne d&eacute;corative en fin de chapitre.
   Comme nous marquons d&eacute;j&agrave; les <a href="#chap_head">En-t&ecirc;tes de
   chapitre</a>, il est inutile d'utiliser la marque de pause.
</p>
<p>Dans l'interface de correction, vous pouvez copier/coller la marque <tt>&lt;tb&gt;</tt>.
</p>
<!-- END RR -->
<br>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Exemple de pause">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Exemple d'image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="tbreak.png" alt="thought break"
          width="500" height="264"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
    <tr>
      <td width="100%" valign="top">
    <p><tt>
    like the gentleman with the spiritual hydrophobia<br>
    in the latter end of Uncle Tom's Cabin.<br>
    Unconsciously Mr. Dixon has done his best to<br>
    prove that Legree was not a fictitious character.</tt>
    </p>
    <p><tt>&lt;tb&gt;</tt>
    </p>
    <p><tt>
    Joel Chandler Harris, Harry Stillwell Edwards,<br>
    George W. Cable, Thomas Nelson Page,<br>
    James Lane Allen, and Mark Twain are Southern<br>
    men in Mr. Griffith's class. I recommend</tt>
    </p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="illust">Illustrations</a></h3>
<p>Le texte pour une illustration (l&eacute;gende) doit &ecirc;tre entour&eacute; de <tt>[Illustration:&nbsp;le-texte]</tt>.
   Gardez la l&eacute;gende comme elle est imprim&eacute;e, avec ses retours &agrave; la
   ligne, italiques, etc.
</p>
<p>S'il n'y a pas de l&eacute;gende, indiquez juste <tt>[Illustration]</tt> &agrave; l'endroit
   o&ugrave; elle se trouve.
</p>
<p>Si l'illustration est au milieu d'un paragraphe ou sur le c&ocirc;t&eacute;,
   d&eacute;placez la marque d'illustration soit au-dessus, soit
   en-dessous du paragraphe, et mettez une ligne vide avant ou apr&egrave;s la marque
   d'illustration pour la s&eacute;parer du texte du paragraphe. Rejoignez les deux bouts
   du paragraphe qui &eacute;taient s&eacute;par&eacute;s par l'illustration en effa&ccedil;ant les lignes
   vides.
</p>
<p>Si le paragraphe coup&eacute; par l'illustration prend toute la page, ajoutez une
   <tt>*</tt> comme ceci: <tt>*[Illustration: <font color="red">(texte de l'illustration)</font>]</tt>,
   mettez-le tout en haut de la page, et laissez une ligne vide apr&egrave;s.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Illustration">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">
      Exemple d'image:
      </th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="illust.png" alt=""
          width="500" height="525"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
   </tr>
    <tr>
    <td width="100%" valign="top">
      <p><tt>[Illustration: Martha told him that he had always been her ideal and<br>
      that she worshipped him.<br>
      <br>
      &lt;i&gt;Frontispiece&lt;/i&gt;<br>
      <br>
      &lt;i&gt;Her Weight in Gold&lt;/i&gt;]
      </tt></p>
    </td>
    </tr>
  </tbody>
</table>

<br>
<table width="100%" align="center" border="1"  cellpadding="4"
 cellspacing="0" summary="Illustration au milieu d'un paragraphe">
  <tbody>
   <tr>
     <th align="left" bgcolor="cornsilk">Exemple d'image: (Illustration au milieu d'un paragraphe)</th>
   </tr>
   <tr align="left">
     <td width="100%" valign="top"> <img src="illust2.png" alt=""
         width="500" height="514"> <br>
     </td>
   </tr>
   <tr>
     <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
   </tr>
    <tr valign="top">
     <td>
     <p><tt>
     such study are due to Italians. Several of these instruments<br>
     have already been described in this journal, and on the present<br>
     occasion we shall make known a few others that will<br>
     serve to give an idea of the methods employed.<br>
     </tt></p>
     <p><tt>[Illustration: &lt;sc&gt;Fig.&lt;/sc&gt; 1.--APPARATUS FOR THE STUDY OF HORIZONTAL<br>
     SEISMIC MOVEMENTS.]</tt></p>
     <p><tt>
     For the observation of the vertical and horizontal motions<br>
     of the ground, different apparatus are required. The</tt>
     </p>
    </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="footnotes">Notes de bas de page et de fin</a></h3>
<p><b>Les notes de bas de page sont "hors ligne"</b>, autrement dit, le texte de la
   note est en bas de la page et une marque est plac&eacute;e dans le texte, l&agrave; o&ugrave; elle
   est r&eacute;f&eacute;renc&eacute;e.
</p>
<p>Pour le formatage, ceci veut dire que&nbsp;:
</p>
<p>1. Le num&eacute;ro, la lettre, ou un autre caract&egrave;re qui marque la note doit
   &ecirc;tre entour&eacute; de crochets (<tt>[</tt> et <tt>]</tt>) et plac&eacute; sans espace
   imm&eacute;diatement &agrave; c&ocirc;t&eacute; du mot sur lequel porte la
   note<tt>[1]</tt> ou son signe de ponctuation,<tt>[2]</tt> comme dans le texte,
   et dans les deux exemples de cette phrase.
</p>
<p>Parfois, les notes sont marqu&eacute;es par des s&eacute;ries de caract&egrave;res
   sp&eacute;ciaux (*, &dagger;, &Dagger;, &sect;, etc.) Dans ce cas, remplacez-les
   par des lettres majuscules, dans l'ordre (A, B, C, etc.) quand vous formatez.
</p>
<p>2. La note est entour&eacute;e par la marque de note <tt>[Footnote #:&nbsp;</tt> et <tt>]</tt>,
   avec le texte de la note entre les deux, et le num&eacute;ro (ou la lettre)
   de la note &agrave; la place du signe #. Laissez le texte de la note tel qu'il est
   imprim&eacute;, avec ses retours &agrave; la ligne, italiques, etc. Laissez la note en bas de
   la page. Utilisez bien la m&ecirc;me marque de note dans la note et dans le texte (l&agrave;
   o&ugrave; la note est r&eacute;f&eacute;renc&eacute;e).
   Placez chaque note sur une ligne s&eacute;par&eacute;e, dans l'ordre d'apparition, avec une ligne vide
   entre chaque note s'il y a plusieurs notes.
</p>
<!-- END RR -->

<p>Pour certains livres, le chef de projet vous demandera peut-&ecirc;tre de mettre
   les notes de bas de page en ligne. Dans ce cas, lisez les "<a href="#comments">Commentaires de
   projet</a>".
</p>
<p>Pour avoir un exemple de note de bas de page, voyez l'exemple de la section
   <a href="#page_hf">En-t&ecirc;tes et bas de page</a>.
</p>
<p>Si vous voyez une note en bas d'une page, sans marque de note dans le texte,
   surtout si elle d&eacute;marre au milieu d'une phrase ou d'un mot, c'est probablement
   la continuation d'une note de bas de page de la page pr&eacute;c&eacute;dente. Laissez-la au
   bas de la page, avec les autres notes de bas de page, et entourez-la par
   <tt>*[Footnote: <font color="red">(texte de la note)</font>]</tt> (sans marque ou
   num&eacute;ro de note). L'&eacute;toile indique que c'est une continuation de note, et attire
   l'attention du post-processeur.
</p>
<p>Si une note continue sur la page suivante (la page s'arr&ecirc;te avant la note),
   laissez la note &agrave; la fin de la page, et mettez un ast&eacute;risque <tt>*</tt>
   l&agrave; o&ugrave; la note s'arr&ecirc;te, comme ceci <tt>[Footnote 1:
   <font color="red">texte-texte-texte</font>]*</tt>. (Le <tt>*</tt> indique que la note s'arr&ecirc;te
   pr&eacute;matur&eacute;ment, et attire l'attention du post-processeur qui fusionnera les deux
   parties de la note.
</p>
<p>Si une note fragment&eacute;e sur plusieurs pages commence ou s'arr&ecirc;te sur un mot
   coup&eacute;, marquez le mot coup&eacute; <b>et</b> la note par une &eacute;toile, comme ceci.<br>
   <tt>[Footnote 1: Cette note se poursuit et son dernier mot se poursuit aus-*]*</tt><br>
   pour le premier fragment, et <br>
   <tt>*[Footnote: *si sur la page suivante.]</tt><br>
   pour le second fragment.
</p>
<p>Si une note est r&eacute;f&eacute;renc&eacute;e dans le texte mais n'appara&icirc;t
   pas sur cette page, laissez la marque de note entour&eacute;e de crochets, comme
   d'habitude. Ce cas est courant dans les livres scientifiques et techniques, o&ugrave;
   les notes sont souvent group&eacute;es en fin de chapitre.
</p>

<table width="100%" border="1"  cellpadding="4" cellspacing="0" 
align="center" summary="Exemples de notes de bas de page">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Texte original:</th>
    </tr>
    <tr>
      <td valign="top">
    The principal persons involved in this argument were Caesar<sup>1</sup>, former military<br>
    leader and Imperator, and the orator Cicero<sup>2</sup>. Both were of the aristocratic<br>
    (Patrician) class, and were quite wealthy.<br>
    <hr align="left" width="50%" noshade size="2">
    <font size=-1><sup>1</sup> Gaius Julius Caesar.</font><br>
    <font size=-1><sup>2</sup> Marcus Tullius Cicero.</font>
      </td>
    </tr>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Format&eacute; avec notes de bas de page:</th>
    </tr>
      <tr valign="top">
      <td>
    <tt>The principal persons involved in this argument were Caesar[1], former military</tt><br>
    <tt>leader and Imperator, and the orator Cicero[2]. Both were of the aristocratic</tt><br>
    <tt>(Patrician) class, and were quite wealthy.</tt><br>
    <br>
    <tt>[Footnote 1: Gaius Julius Caesar.]</tt><br>
    <br>
    <tt>[Footnote 2: Marcus Tullius Cicero.]</tt>
      </td>
    </tr>
  </tbody>
</table>

<p>Pour certains livres, les notes de bas de page sont s&eacute;par&eacute;es du texte
   principal par une ligne horizontale. Nous ne le ferons pas; laissez donc juste
   une ligne blanche entre le texte et la note (voir exemple ci-dessus).
</p>
<p>Les <b>notes de fin</b> sont simplement des notes de bas de page qui ont &eacute;t&eacute;
   plac&eacute;es en fin de chapitre, ou en fin de livre, au lieu d'&ecirc;tre en fin de page.
   Traitez-les comme des notes de bas de page. Quand vous voyez la r&eacute;f&eacute;rence dans le
   texte, entourez-la par des crochets. Si vous corrigez une des pages de fin, l&agrave;
   o&ugrave; sont les notes, entourez la note par <tt>[Footnote #:
   <font color="red">(texte)</font>]</tt> en rempla&ccedil;ant le signe # par le num&eacute;ro ou la
   marque de la note. Mettez une ligne blanche apr&egrave;s chaque note, pour qu'elles
   apparaissent comme des paragraphes s&eacute;par&eacute;s.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p>Les <b>notes sur de la <a href="#poetry">po&eacute;sie</a>, ou sur des <a href="#tables">tables</a></b>
   sont trait&eacute;es comme les autres. Marquez la r&eacute;f&eacute;rence, et laissez
   les notes en bas de la page. Le post-processeur d&eacute;cidera de leur emplacement final.
</p>

<table width="100%" align="center" border="1" cellpadding="4"
cellspacing="0" summary="Notes de bas de page">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Po&eacute;sie annot&eacute;e:</th></tr>
    <tr>
      <td valign="top">
    Mary had a little lamb<sup>1</sup><br>
    &nbsp;&nbsp;&nbsp;Whose fleece was white as snow<br>
    And everywhere that Mary went<br>
    &nbsp;&nbsp;&nbsp;The lamb was sure to go!<br>
    <hr align="left" width="50%" noshade size=2>
    <font size=-2><sup>1</sup> This lamb was obviously of the Hampshire breed,<br>
    well known for the pure whiteness of their wool.</font>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
    <tr>
      <td valign="top">
    <tt>/*<br>
    Mary had a little lamb[1]<br>
    &nbsp;&nbsp;Whose fleece was white as snow<br>
    And everywhere that Mary went<br>
    &nbsp;&nbsp;The lamb was sure to go!<br>
    */<br>
    <br>
    [Footnote 1: This lamb was obviously of the Hampshire breed,<br>
    well known for the pure whiteness of their wool.]<br>
    </tt>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="para_side">Commentaires en marge</a></h3>
<p>Certains livres ont de petites descriptions des paragraphes sur le c&ocirc;t&eacute; du
   texte. Ce sont les notes en marge ("Sidenotes"). D&eacute;placez ces notes juste au-dessus du paragraphe
   auquel elles appartiennent. Une note en marge est entour&eacute;e par les marques
   suivantes: <tt>[Sidenote:&nbsp;</tt> avant et <tt>]</tt> apr&egrave;s. Formatez la note
   pour qu'elle ressemble au texte imprim&eacute;, en gardant les retours &agrave; la ligne, les
   italiques, etc. Laissez une ligne blanche apr&egrave;s la note, pour qu'elle ne se
   m&eacute;lange pas avec le paragraphe durant la phase de post-processing.
</p>
<p>S'il y a plusieurs notes pour un m&ecirc;me paragraphe, mettez-les l'une apr&egrave;s
   l'autre au d&eacute;but du paragraphe. S&eacute;parez-les par des lignes blanches.
</p>
<p>Si le paragraphe a commenc&eacute; sur une page pr&eacute;c&eacute;dente, mettez la note en haut
   de la page et marquez-la avec une ast&eacute;risque (&nbsp;<tt>*</tt>&nbsp;) de mani&egrave;re &agrave; ce que
   le post-processeur puisse voir qu'elle appartient &agrave; la page pr&eacute;c&eacute;dente. De cette
   mani&egrave;re: <tt>*[Sidenote: <font color="red">texte de la note</font>]</tt>. Le post-processeur
   d&eacute;placera la note &agrave; l'endroit appropri&eacute;.
</p>
<p>Parfois, le chef de projet vous demandera de placer la note &agrave; c&ocirc;t&eacute; de la
   phrase &agrave; laquelle elle s'applique, et pas au d&eacute;but ou &agrave; la fin du paragraphe.
   Dans ce cas, ne les s&eacute;parez pas par des lignes blanches.
</p>
<!-- END RR -->

  <table width="100%" align="center" border="1" cellpadding="4"
       cellspacing="0" summary="Notes en marge"> <col width="128*">
  <tbody>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Exemple d'image:</th>
    </tr>
    <tr valign="top">
      <td width="100%" align="left"><img src="side.png" alt=""
          width="550" height="800"><br>
      </td>
    </tr>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
    </tr>
    <tr valign="top">
      <td width="100%">
    <p><tt>
    *[Sidenote: Burning<br>
    discs<br>
    thrown into<br>
    the air.]<br>
    <br>
    that such as looked at the fire holding a bit of larkspur<br>
    before their face would be troubled by no malady of the<br>
    eyes throughout the year.[1] Further, it was customary at<br>
    W&uuml;rzburg, in the sixteenth century, for the bishop's followers<br>
    to throw burning discs of wood into the air from a mountain<br>
    which overhangs the town. The discs were discharged by<br>
    means of flexible rods, and in their flight through the darkness<br>
    presented the appearance of fiery dragons.[2]<br>
    <br>
    [Sidenote: The Midsummer<br>
    fires in<br>
    Swabia.]<br>
    <br>
    [Sidenote: Omens<br>
    drawn from<br>
    the leaps<br>
    over the<br>
    fires.]<br>
    <br>
    [Sidenote: Burning<br>
    wheels<br>
    rolled<br>
    down hill.]<br>
    <br>
    In the valley of the Lech, which divides Upper Bavaria<br>
    from Swabia, the midsummer customs and beliefs are, or<br>
    used to be, very similar. Bonfires are kindled on the<br>
    mountains on Midsummer Day; and besides the bonfire<br>
    a tall beam, thickly wrapt in straw and surmounted by a<br>
    cross-piece, is burned in many places. Round this cross as<br>
    it burns the lads dance with loud shouts; and when the<br>
    flames have subsided, the young people leap over the fire in<br>
    pairs, a young man and a young woman together. If they<br>
    escape unsmirched, the man will not suffer from fever, and<br>
    the girl will not become a mother within the year. Further,<br>
    it is believed that the flax will grow that year as high as<br>
    they leap over the fire; and that if a charred billet be taken<br>
    from the fire and stuck in a flax-field it will promote the<br>
    growth of the flax.[3] Similarly in Swabia, lads and lasses,<br>
    hand in hand, leap over the midsummer bonfire, praying<br>
    that the hemp may grow three ells high, and they set fire<br>
    to wheels of straw and send them rolling down the hill.<br>
    Among the places where burning wheels were thus bowled<br>
    down hill at Midsummer were the Hohenstaufen mountains<br>
    in Wurtemberg and the Frauenberg near Gerhausen.[4]<br>
    At Deffingen, in Swabia, as the people sprang over the mid-*<br>
    <br>
    [Footnote 1: &lt;i&gt;Op. cit.&lt;/i&gt; iv. 1. p. 242. We have<br>
    seen (p. 163) that in the sixteenth<br>
    century these customs and beliefs were<br>
    common in Germany. It is also a<br>
    German superstition that a house which<br>
    contains a brand from the midsummer<br>
    bonfire will not be struck by lightning<br>
    (J. W. Wolf, &lt;i&gt;Beitr&auml;ge zur deutschen<br>
    Mythologie&lt;/i&gt;, i. p. 217, &sect; 185).]<br>
    <br>
    [Footnote 2: J. Boemus, &lt;i&gt;Mores, leges et ritus<br>
    omnium gentium&lt;/i&gt; (Lyons, 1541), p.<br>
    226.]<br>
    <br>
    [Footnote 3: Karl Freiherr von Leoprechting,<br>
    &lt;i&gt;Aus dem Lechrain&lt;/i&gt; (Munich, 1855),<br>
    pp. 181 &lt;i&gt;sqq.&lt;/i&gt;; W. Mannhardt, &lt;i&gt;Der<br>
    Baumkultus&lt;i&gt;, p. 510.]<br>
    <br>
    [Footnote 4: A. Birlinger, &lt;i&gt;Volksth&uuml;mliches aus<br>
    Schwaben&lt;/i&gt; (Freiburg im Breisgau, 1861-1862),<br>
    ii. pp. 96 &lt;i&gt;sqq.&lt;/i&gt;, &sect; 128, pp. 103<br>
    &lt;i&gt;sq.&lt;/i&gt;, &sect; 129; &lt;i&gt;id.&lt;/i&gt;, &lt;i&gt;Aus Schwaben&lt;/i&gt; (Wiesbaden,<br>
    1874), ii. 116-120; E. Meier,<br>
    &lt;i&gt;Deutsche Sagen, Sitten und Gebr&auml;uche<br>
    aus Schwaben&lt;/i&gt; (Stuttgart, 1852), pp.<br>
    423 &lt;i&gt;sqq.&lt;/i&gt;; W. Mannhardt, &lt;i&gt;Der Baumkultus&lt;/i&gt;,<br>
    p. 510.]<br>
    </tt></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="block_qt">Blocs de citations</a></h3>
<p>Entourez les blocs de citations par les marques <tt>/#</tt> avant et <tt>#/</tt>
   apr&egrave;s. Laissez une ligne vide entre ces marques et le reste du texte.
</p>
<p>&Agrave; part l'ajout de ces marques, les blocs de citations se traitent comme du texte normal.
</p>
<p>Les blocs de citations sont des citations longues (typiquement plusieurs
   lignes, parfois m&ecirc;me plusieurs pages) incluses dans un livre. Elles sont souvent
   (mais pas toujours) imprim&eacute;es en caract&egrave;res plus petits, ou avec de plus grandes marges, 
   ou parfois les deux.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Bloc de citation">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Exemple d'image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="bquote.png" alt="" width="500" height="475"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <p><tt>later day was welcomed in their home on the Hudson.<br>
        Dr. Bakewell's contribution was as follows:[24]</tt></p>
        <p><tt>/#<br>
        The uncertainty as to the place of Audubon's birth has been<br>
        put to rest by the testimony of an eye witness in the person<br>
        of old Mandeville Marigny now dead some years. His repeated<br>
        statement to me was, that on his plantation at Mandeville,<br>
        Louisiana, on Lake Ponchartrain, Audubon's mother was<br>
        his guest; and while there gave birth to John James Audubon.<br>
        Marigny was present at the time, and from his own lips, I have,<br>
        as already said, repeatedly heard him assert the above fact.<br>
        He was ever proud to bear this testimony of his protection<br>
        given to Audubon's mother, and his ability to bear witness as<br>
        to the place of Audubon's birth, thus establishing the fact that<br>
        he was a Louisianian by birth.<br>
        #/<br></tt>
        </p>
        <p><tt>We do not doubt the candor and sincerity of the<br>
        excellent Dr. Bakewell, but are bound to say that the<br>
        incidents as related above betray a striking lapse of<br>
        </tt></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="mult_col">Colonnes Multiples</a></h3>
<p>R&eacute;unissez les colonnes multiples en une seule colonne.
</p>
<p>Placez la colonne la plus &agrave; gauche en premier puis les autres colonnes
   &agrave; sa suite. Vous ne devez rien faire de particulier pour marquer la
   s&eacute;paration des colonnes, rejoignez-les simplement.
</p>
<p>Si le contenu des colonnes est une liste, mettez un <tt>/*</tt> avant le d&eacute;but de la
   liste et <tt>*/</tt> apr&egrave;s, pour &eacute;viter de perdre les retours &agrave; la ligne pendant la phase de
   post-processing. Mettez une ligne vide avant le <tt>/*</tt> et une autre apr&egrave;s le <tt>*/</tt>.
</p>
<p>Voir aussi les sections <a href="#bk_index">Index</a>, <a href="#lists">Listes</a> et
   <a href="#tables">Tables</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="lists">Listes de choses</a></h3>
<p>Marquez ces listes comme de la po&eacute;sie, avec une ligne <tt>/*</tt> avant et
   une ligne <tt>*/</tt> apr&egrave;s. Ins&eacute;rez une ligne blanche avant <tt>/*</tt> et une autre
   apr&egrave;s <tt>*/</tt>. Ainsi, le post-processeur saura qu'il doit garder ces lignes s&eacute;par&eacute;es.
   Marquez ainsi des listes qui ne doivent pas &ecirc;tre reformat&eacute;es, comme des listes
   de questions/r&eacute;ponses, des ingr&eacute;dients dans une recette, etc.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" 
  summary="Exemple de liste">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Texte original:</th></tr>
    <tr>
      <td valign="top">
<pre>
Andersen, Hans Christian   Daguerre, Louis J. M.    Melville, Herman
Bach, Johann Sebastian     Darwin, Charles          Newton, Isaac
Balboa, Vasco Nunez de     Descartes, Ren&eacute;          Pasteur, Louis
Bierce, Ambrose            Earhart, Amelia          Poe, Edgar Allan
Carroll, Lewis             Einstein, Albert         Ponce de Leon, Juan
Churchill, Winston         Freud, Sigmund           Pulitzer, Joseph
Columbus, Christopher      Lewis, Sinclair          Shakespeare, William
Curie, Marie               Magellan, Ferdinand      Tesla, Nikola
</pre>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
    <tr>
      <td valign="top">
    <tt>
    /*<br>
    Andersen, Hans Christian<br>
    Bach, Johann Sebastian<br>
    Balboa, Vasco Nunez de<br>
    Bierce, Ambrose<br>
    Carroll, Lewis<br>
    Churchill, Winston<br>
    Columbus, Christopher<br>
    Curie, Marie<br>
    Daguerre, Louis J. M.<br>
    Darwin, Charles<br>
    Descartes, Ren&eacute;<br>
    Earhart, Amelia<br>
    Einstein, Albert<br>
    Freud, Sigmund<br>
    Lewis, Sinclair<br>
    Magellan, Ferdinand<br>
    Melville, Herman<br>
    Newton, Isaac<br>
    Pasteur, Louis<br>
    Poe, Edgar Allan<br>
    Ponce de Leon, Juan<br>
    Pulitzer, Joseph<br>
    Shakespeare, William<br>
    Tesla, Nikola<br>
    */
    </tt>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="tables">Tableaux</a></h3>
<p>Marquez les tables de mani&egrave;re &agrave; ce que le post-processeur puisse les trouver,
   en les entourant de <tt>/*</tt> et <tt>*/</tt>, comme pour la <a href="#poetry">po&eacute;sie</a>.
   Mettez une ligne blanche avant <tt>/*</tt> et une autre apr&egrave;s <tt>*/</tt>. Formatez les tables
   avec des espaces de mani&egrave;re &agrave; ce qu'elles ressemblent approximativement au
   tableau original. Ne faites pas de tableau plus large que 75 caract&egrave;res.
   Les r&egrave;gles du projet Gutenberg ajoutent: "... sauf si vous ne pouvez pas
   faire autrement. Mais JAMAIS plus de 80 caract&egrave;res."
</p>
<p>Pour aligner les champs, n'utilisez pas de tabulations. Seulement des espaces
   (les tabulations ont des tailles diff&eacute;rentes suivant l'&eacute;diteur de texte).
</p>
<p>Il est souvent difficile de formater des tables en texte ASCII; faites
   de votre mieux. Utilisez une fonte &agrave; espacement fixe, 
   comme <a href="font_sample.php">DPCustomMono</a> ou Courier.
   Le but est toujours de pr&eacute;server ce que l'auteur a voulu dire, tout en produisant
   un texte &eacute;lectronique lisible. Il faudra parfois abandonner le format original
   de la table. Regardez les <a href="#comments">commentaires de projet</a>, et le forum du projet:
   d'autres correcteurs se sont peut-&ecirc;tre mis d'accord sur un format sp&eacute;cifique.
   Vous trouverez peut-&ecirc;tre des mod&egrave;les utiles
   dans la <a href="<? echo $Gallery_of_Table_Layouts_URL; ?>">Galerie de tables</a> sur
   le forum.
</p>
<p>Les <b>notes de bas de page</b> dans les tableaux doivent aller &agrave; la fin du
   tableau. Voyez <a href="#footnotes">notes de bas de page</a> pour plus de details.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Table, Exemple 1">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Exemple d'image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="table1.png" alt="" width="500" height="142"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
    <tr>
      <td width="100%" valign="top">
<pre><tt>/*
Deg. C.   Millimeters of Mercury.    Gasolene.
               Pure Benzene.

 -10&deg;               13.4                 43.5
   0&deg;               26.6                 81.0
 +10&deg;               46.6                132.0
  20&deg;               76.3                203.0
  40&deg;              182.0                301.8
*/</tt></pre>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Table, Exemple 2">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Exemple d'image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="table2.png" alt="" width="500" height="304"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
    <tr>
      <td width="100%" valign="top">
<pre><tt>/*
TABLE II.

-----------------------+----+-----++-------------------------+----+------
                       | C  |     ||                         |  C |
Flat strips compared   | o  |     ||                         |  o |
with round wire 30 cm. | p  |Iron.|| Parallel wires 30 cm.   |  p | Iron.
in length.             | p  |     || in length.              |  p |
                       | e  |     ||                         |  e |
                       | r  |     ||                         |  r |
                       | .  |     ||                         |  . |
-----------------------+----+-----++-------------------------+----+------
Wire 1 mm. diameter    | 20 | 100 || Wire 1 mm. diameter     | 20 |  100
-----------------------+----+-----++-------------------------+----+------
        STRIPS.        |    |     ||       SINGLE WIRE.      |    |
0.25 mm. thick, 2 mm.  |    |     ||                         |    |
  wide                 | 15 |  35 || 0.25 mm. diameter       | 16 |   48
Same, 5 mm. wide       | 13 |  20 || Two  similar wires      | 12 |   30
 "   10  "    "        | 11 |  15 || Four    "      "        |  9 |   18
 "   20  "    "        | 10 |  14 || Eight   "      "        |  8 |   10
 "   40  "    "        |  9 |  13 || Sixteen "      "        |  7 |    6
Same strip rolled up in|    |     || Same, 16 wires bound    |    |
  the form of wire     | 17 |  15 ||   close together        | 18 |   12
-----------------------+----+-----++-------------------------+----+------
*/</tt></pre>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="poetry">Po&eacute;sie/&Eacute;pigrammes</a></h3>
<p>Cette section s'applique aux po&egrave;mes et aux &eacute;pigrammes dans un livre qui n'est
   pas un livre de po&eacute;sie. Pour les livres de po&eacute;sie, voir les
   <a href="doc-poet.php">Directives sp&eacute;ciales pour les livres de po&eacute;sie</a> (en anglais).
</p>
<p>Marquez les po&eacute;sies ou les &eacute;pigrammes de sorte que le post-processeur puisse les
   trouver plus vite. Ins&eacute;rez une nouvelle ligne avec un <tt>/*</tt> au d&eacute;but de la
   po&eacute;sie ou &eacute;pigramme et une ligne s&eacute;par&eacute;e avec <tt>*/</tt>
   &agrave; la fin. Mettez une ligne blanche avant <tt>/*</tt> et une autre apr&egrave;s <tt>*/</tt>.
</p>
<p>Pr&eacute;servez l'indentation relative des vers les uns par rapport aux autres, en
   ajoutant 2, 4, 6... espaces avant le d&eacute;but du vers de fa&ccedil;on &agrave; ressembler
   au texte imprim&eacute;.
</p>
<p>Quand un vers est trop long pour la page, la plupart des &eacute;ditions cassent le
   vers et impriment la fin sur une ligne s&eacute;par&eacute;e, vers la marge de droite. Dans
   ces cas-l&agrave;, rejoignez les deux parties du vers de fa&ccedil;on &agrave; ne former qu'une
   ligne. Les lignes de continuation commencent souvent par une minuscule, et
   apparaissent irr&eacute;guli&egrave;rement alors que l'indentation normale appara&icirc;t
   r&eacute;guli&egrave;rement au cours du po&egrave;me.
</p>
<p>Ne passez pas votre temps &agrave; centrer les lignes de po&eacute;sie, m&ecirc;me si elles sont
   centr&eacute;es sur la page originale. Calez le texte sur la marge de gauche (en
   indentant les vers les uns par rapport aux autres si n&eacute;cessaire).
</p>
<p>Les <b>notes de bas de page</b> dans la po&eacute;sie se traitent comme les autres notes de
   bas de page. Voyez <a href="#footnotes">notes de bas de page</a> pour plus de details.
</p>
<p>Gardez les <b>num&eacute;ros de vers</b> s'ils sont imprim&eacute;s. Mettez-les &agrave; la fin de la
   ligne, et s&eacute;parez-les du texte par 6 blancs au moins. Voir <a href="#line_no">Num&eacute;ros de
   ligne</a> pour des d&eacute;tails.
</p>
<p>Regardez les <a href="#comments">commentaires de projet</a> pour des instructions
   sp&eacute;cifiques. Souvent, les livres de po&eacute;sie ont des instructions
   sp&eacute;cifiques, souvent dans ces livres ces instructions ne s'appliqueront pas.
</p>
<!-- END RR -->

<br>
<table width="100%" align="center" border="1"  cellpadding="4"
       cellspacing="0" summary="Exemple de po&eacute;sie">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Exemple d'image:</th></tr>
    <tr align="left">
      <th width="100%" valign="top"> <img src="poetry.png" alt=""
          width="500" height="508"> <br>
      </th>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
    <tr>
      <td width="100%" valign="top">

<tt>
to the scenery of his own country:<br></tt>
<p><tt>
/*<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Oh, to be in England<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now that April's there,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;And whoever wakes in England<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sees, some morning, unaware,<br>
That the lowest boughs and the brushwood sheaf<br>
Round the elm-tree bole are in tiny leaf,<br>
While the chaffinch sings on the orchard bough<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;In England--now!</tt>
</p><p><tt>
And after April, when May follows,<br>
And the whitethroat builds, and all the swallows!<br>
Hark! where my blossomed pear-tree in the hedge<br>
Leans to the field and scatters on the clover<br>
Blossoms and dewdrops--at the bent spray's edge--<br>
That's the wise thrush; he sings each song twice over,<br>
Lest you should think he never could recapture<br>
The first fine careless rapture!<br>
And though the fields look rough with hoary dew,<br>
All will be gay, when noontide wakes anew<br>
The buttercups, the little children's dower;<br>
--Far brighter than this gaudy melon-flower!<br>
*/<br></tt>
</p><p><tt>
So it runs; but it is only a momentary memory;<br>
and he knew, when he had done it, and to his</tt>
</p>

      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="line_no">Num&eacute;ros de ligne</a></h3>
<p>Gardez les num&eacute;ros de ligne. Placez-les au moins 6 espaces &agrave; droite du texte,
   en fin de ligne, m&ecirc;me si, sur l'image, ces num&eacute;ros sont &agrave; gauche.
</p>
<p>Il y a souvent des num&eacute;ros de lignes dans la marge, sur les livres de
   po&eacute;sie, tous les 5, 10 ou 20 vers. Nous gardons ces num&eacute;ros car
   ils sont utiles au lecteur.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="letter">Lettres (courrier)</a></h3>
<p>Formatez les lettres comme vous feriez pour des <a href="#para_space">paragraphes</a>.
   Au lieu d'indenter, ins&eacute;rez une ligne vierge avant le d&eacute;but de la lettre.
</p>
<p>Marquez les en-t&ecirc;tes et les fins de lettres (adresse, date, salutations, signature) avec un
   <tt>/*</tt> sur une ligne isol&eacute;e avant, et <tt>*/</tt> sur une ligne isol&eacute;e
   apr&egrave;s, comme si c'&eacute;tait de la po&eacute;sie. Mettez une ligne blanche entre ces marques
   et le reste du texte. De cette fa&ccedil;on, les retours &agrave; la ligne seront conserv&eacute;s au cours de
   la phase d'assemblage lors du post-processing.
</p>
<p>Ne les indentez pas, m&ecirc;me si, sur l'original, elles sont indent&eacute;es ou
   justifi&eacute;es &agrave; droite. Calez-les sur la marge de gauche. Le post-processeur
   les formatera correctement.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1"  cellpadding="4"
       cellspacing="0" summary="Exemple de lettre">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Exemple d'image:</th></tr>
    <tr align="left">
      <th width="100%" valign="top"> <img src="letter.png" alt=""
          width="500" height="217"> <br>
      </th>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
    <tr>
      <td width="100%" valign="top">
<p><tt>&lt;i&gt;John James Audubon to Claude Fran&ccedil;ois Rozier&lt;/i&gt;</tt></p>
<p><tt>[Letter No. 1, addressed]</tt></p>
<p><tt>/*<br>
&lt;sc&gt;M. Fr. Rozier&lt;/sc&gt;,<br>
Merchant-Nantes.<br>
&lt;sc&gt;New York&lt;/sc&gt;, &lt;i&gt;10 January, 1807.&lt;/i&gt;</tt></p>
<p><tt>
&lt;sc&gt;Dear Sir:&lt;/sc&gt;<br>
*/</tt></p>
<p><tt>
We have had the pleasure of receiving by the &lt;i&gt;Penelope&lt;/i&gt; your<br>
consignment of 20 pieces of linen cloth, for which we send our<br>
thanks. As soon as we have sold them, we shall take great<br>
pleasure in making our return.</tt>
</p>

      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="blank_pg">Page blanche</a></h3>
<p>Merci de mettre comme texte <tt>[Blank Page]</tt> si le texte et l'image sont vides.
</p>
<p>Si le texte seulement (ou l'image seulement) est vide, suivez la
   proc&eacute;dure indiqu&eacute;es dans le cas d'une <a href="#bad_image">mauvaise image</a>
   ou d'un <a href="#bad_text">mauvais texte</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="title_pg">Page de titre/fin</a></h3>
<p>Laissez tout comme c'est imprim&eacute;, m&ecirc;me si c'est tout en majuscules, ou en
   majuscules et minuscules. Gardez la date de copyright ou de publication.
</p>
<p>Certaines livres, souvent, mettent la premi&egrave;re lettre
   grande et orn&eacute;e. Tapez simplement la lettre.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Example de page de titre">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">
      Exemple d'image:
      </th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="title.png" width="500"
          height="520" alt="title page image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
      <p><tt>GREEN FANCY</tt>
      </p>
      <p><tt>BY</tt></p>
      <p><tt>GEORGE BARR McCUTCHEON</tt></p>
      <p><tt>AUTHOR OF "GRAUSTARK," "THE HOLLOW OF HER HAND,"<br>
         "THE PRINCE OF GRAUSTARK," ETC.</tt></p>
      <p><tt>&lt;i&gt;WITH FRONTISPIECE BY&lt;/i&gt;<br>
         &lt;i&gt;C. ALLAN GILBERT&lt;/i&gt;</tt></p>
      <p><tt>NEW YORK<br>
         DODD, MEAD AND COMPANY<br>
         1917</tt></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="toc">Table des mati&egrave;res</a></h3>
<p>Laissez le texte de la table des mati&egrave;res comme il est imprim&eacute; (m&ecirc;me si c'est
   tout en capitales). Encadrez la table par <tt>/*</tt> (pr&eacute;c&eacute;d&eacute;e d'une
   ligne blanche) au d&eacute;but et <tt>*/</tt> (suivie d'une ligne blanche) &agrave; la
   fin. Gardez les num&eacute;ros de page et mettez-les 6 espaces apr&egrave;s le texte, en fin de ligne.
</p>
<p>Enlevez les points ou les ast&eacute;risques qui forment des lignes horizontales (points de conduite), 
   entre le texte et le num&eacute;ro.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="TdM">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">
      Exemple d'image:
      </th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top">
      <p><img src="tablec.png" alt="" width="500" height="650"></p>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
      <p><tt><br><br><br><br>CONTENTS<br><br></tt></p>
      <p><tt>/*<br>
          CHAPTER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PAGE<br>
          <br>
          I. &lt;sc&gt;The First Wayfarer and the Second Wayfarer<br>
          Meet and Part on the Highway&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1<br>
          <br>
          II. &lt;sc&gt;The First Wayfarer Lays His Pack Aside and<br>
          Falls in with Friends&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;15<br>
          <br>
          III. &lt;sc&gt;Mr. Rushcroft Dissolves, Mr. Jones Intervenes,<br>
          and Two Men Ride Away&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;33<br>
          <br>
          IV. &lt;sc&gt;An Extraordinary Chambermaid, a Midnight<br>
          Tragedy, and a Man Who Said "Thank You"&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;50<br>
          <br>
          V. &lt;sc&gt;The Farm-boy Tells a Ghastly Story, and an<br>
          Irishman Enters&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;67<br>
          <br>
          VI. &lt;sc&gt;Charity Begins Far from Home, and a Stroll in<br>
          the Wildwood Follows&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;85<br>
          <br>
          VII. &lt;sc&gt;Spun-gold Hair, Blue Eyes, and Various Encounters&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;103<br>
          <br>
          VIII. &lt;sc&gt;A Note, Some Fancies, and an Expedition in<br>
          Quest of Facts&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;120<br>
          <br>
          IX. &lt;sc&gt;The First Wayfarer, the Second Wayfarer, and<br>
          the Spirit of Chivalry Ascendant&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;134<br>
          <br>
          X. &lt;sc&gt;The Prisoner of Green Fancy, and the Lament of<br>
          Peter the Chauffeur&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;148<br>
          <br>
          XI. &lt;sc&gt;Mr. Sprouse Abandons Literature at an Early<br>
          Hour in the Morning&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;167<br>
          <br>
          XII. &lt;sc&gt;The First Wayfarer Accepts an Invitation, and<br>
          Mr. Dillingford Belabors a Proxy&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;183<br>
          <br>
          XIII. &lt;sc&gt;The Second Wayfarer Receives Two Visitors at<br>
          Midnight&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;199<br>
          <br>
          XIV. &lt;sc&gt;A Flight, a Stone-cutter's Shed, and a Voice<br>
          Outside&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;221<br>
          */<br>
      </tt></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="bk_index">Index</a></h3>
<p>Laissez les num&eacute;ros de page dans les index. Entourez l'index par deux lignes
   <tt>/*</tt> et <tt>*/</tt>, avec une ligne blanche avant <tt>/*</tt> et une
   autre apr&egrave;s <tt>*/</tt>. N'alignez pas les num&eacute;ros les uns sur les
   autres (comme sur l'image): mettez simplement une virgule ou un point-virgule,
   suivi du num&eacute;ro de page.
</p>
<p>Parfois, les index sont imprim&eacute;s sur deux colonnes. L'espace est plus &eacute;troit,
   et une entr&eacute;e donn&eacute;e de l'index peut &ecirc;tre coup&eacute;e en deux lignes.
   Rejoignez les deux parties.
</p>
<p>Dans des index, avec cette r&egrave;gle, il est possible d'avoir une
   ligne tr&egrave;s longue. Ce n'est pas grave, le post-processeur le g&egrave;rera.
</p>
<p>Mettez une ligne blanche entre chaque entr&eacute;e de l'index.
</p>
<p>Pour les listes des sous-sujets dans un index, commencez chacun sur une
   nouvelle ligne, et indentez de 2 espaces.
</p>
<p>Si un index est en plusieurs sections, (A, B, C...), traitez-les comme des
   <a href="#sect_head">en-t&ecirc;tes de section</a>, en les s&eacute;parant par deux lignes blanches.
</p>
<p>Les vieux livres imprimaient parfois le premier mot de chaque lettre de
   l'index enti&egrave;rement en majuscule; changez ces mots pour que le style corresponde
   &agrave; celui utilis&eacute; dans le reste de l'index.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1"  cellpadding="4" 
cellspacing="0" summary="Rejoindre les lignes des index">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte original:</th>
    </tr>
    <tr>
      <td valign="top">
    Elizabeth I, her royal Majesty the<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Queen, 123, 144-155.<br>
    &nbsp;&nbsp;birth of, 145.<br>
    &nbsp;&nbsp;christening, 146-147.<br>
    &nbsp;&nbsp;death and burial, 152.<br>
    <br>
    Ethelred II, the Unready, 33.
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;: (en rejoignant les lignes)</th>
    </tr>
    <tr>
      <td valign="top">
    <tt><br>/*<br>
    Elizabeth I, her royal Majesty the Queen, 123, 144-155.<br>
    &nbsp;&nbsp;birth of, 145.<br>
    &nbsp;&nbsp;christening, 146-147.<br>
    &nbsp;&nbsp;death and burial, 152.<br>
    <br>
    Ethelred II, the Unready, 33.<br>
    */</tt>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4" 
cellspacing="0" summary="Alignement des sous-sujets">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Texte original:</th></tr>
    <tr>
      <td valign="top">
    Hooker, Jos., maj. gen. U. S. V., 345; assigned<br>
    &nbsp;&nbsp;to command Porter's corps, 350; afterwards,<br>
    &nbsp;&nbsp;McDowell's, 367; in pursuit of Lee, 380;<br>
    &nbsp;&nbsp;at South Mt., 382; unacceptable to Halleck,<br>
    &nbsp;&nbsp;retires from active service, 390.<br>
    <br>
    Hopkins, Henry H., 209; notorious secessionist in<br>
    &nbsp;&nbsp;Kanawha valley, 217; controversy with Gen.<br>
    &nbsp;&nbsp;Cox over escaped slave, 233.<br>
    <br>
    Hosea, Lewis M., 187; capt. on Gen. Wilson's staff, 194.<br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;: (avec
         sous-sujets align&eacute;s)</th>
    </tr>
    <tr>
      <td valign="top">
    <tt><br>/*<br>
    Hooker, Jos., maj. gen. U. S. V., 345;<br>
    &nbsp;&nbsp;assigned to command Porter's corps, 350;<br>
    &nbsp;&nbsp;afterwards, McDowell's, 367;<br>
    &nbsp;&nbsp;in pursuit of Lee, 380;<br>
    &nbsp;&nbsp;at South Mt., 382;<br>
    &nbsp;&nbsp;unacceptable to Halleck, retires from active service, 390.<br>
    <br>
    Hopkins, Henry H., 209;<br>
    &nbsp;&nbsp;notorious secessionist in Kanawha valley, 217;<br>
    &nbsp;&nbsp;controversy with Gen. Cox over escaped slave, 233.<br>
    <br>
    Hosea, Lewis M., 187;<br>
    &nbsp;&nbsp;capt. on Gen. Wilson's staff, 194.<br>
    */</tt>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="play_n">Th&eacute;&acirc;tre</a></h3>
<p>Pour toutes les pi&egrave;ces:</p>
<ul compact>
 <li>Traitez les listes de personnages (Dramatis Person&aelig;) comme des <a href="#lists">listes</a>.</li>
 <li>Ins&eacute;rez quatre lignes blanches avant le d&eacute;but d'un acte.</li>
 <li>Ins&eacute;rez deux lignes blanches avant le d&eacute;but d'une sc&egrave;ne.</li>
 <li>Dans les dialogues, ins&eacute;rez une ligne blanche avant chaque prise de
     parole.</li>
 <li>Marquez tous les noms des acteurs en italique/gras/capitales selon qu'ils sont
     en <a href="#italics">Italiques</a> ou <a href="#bold">Gras</a> ou en
     <a href="#word_caps">Capitales</a> dans le texte original.</li>
 <li>Les notes de sc&egrave;ne (didascalies) seront format&eacute;es telles qu'elles sont
     dans le texte original.<br>
     Si la note est sur une ligne isol&eacute;e, laissez-la ainsi; si elle est
     apr&egrave;s une ligne de dialogue, laissez-la ainsi; mais si elle est sur une ligne
     de dialogue et cal&eacute;e contre la marge de droite, laissez six espaces
     entre le dialogue et la didascalie.<br>
     Parfois, une note de sc&egrave;ne commence par un crochet ouvrant, qui n'est jamais referm&eacute;.<br>
     Nous gardons cette convention: ne fermez pas le crochet. Mettez les marques
     d'italiques, s'il y a lieu, &agrave; l'int&eacute;rieur des crochets.</li>
</ul>
<p>Pour les pi&egrave;ces en vers:</p>
<ul compact>
 <li>Les r&egrave;gles de po&eacute;sie s'appliquent aux pi&egrave;ces en vers. Entourez les vers
     par des lignes <tt>/*</tt> et <tt>*/</tt>, comme pour de la po&eacute;sie,
     pour conserver les retours &agrave; la ligne entre chaque vers.
     Mais une didascalie ne doit pas &ecirc;tre entour&eacute;e par <tt>/*</tt> et <tt>*/</tt>
     si elle se trouve sur une ligne s&eacute;par&eacute;e.
     (Ces indications sc&eacute;niques ne sont pas en vers, et peuvent
     donc parfaitement &ecirc;tre reformat&eacute;es comme un paragraphe de
     texte ordinaire, sans conserver les retours &agrave; la ligne de
     l'original: c'est pourquoi elles ne sont pas incluses dans
     les marques /* et */ qui conservent les retours &agrave; la ligne.)</li>
 <li>Si un vers est partag&eacute; entre deux personnages, la seconde partie du vers
     sera imprim&eacute;e indent&eacute;e. Gardez cette identation.</li>
 <li>Si un vers est coup&eacute; parce qu'il est trop long sur la page imprim&eacute;e,
     rejoignez les deux parties du vers sur une m&ecirc;me ligne (comme pour la po&eacute;sie en
     g&eacute;n&eacute;ral).<br>
     Si la seconde partie d'un vers ne fait qu'un mot, alors elle
     sera imprim&eacute;e au-dessus ou au-dessous de la ligne principale, et pr&eacute;c&eacute;d&eacute;e
     d'une "(", au lieu d'avoir une ligne pour elle seule.<br>
     Voir l'<a href="#play4">exemple</a>.</li>
</ul>
<p>Regardez les <a href="#comments">Commentaires de projet</a>, car
   le chef de projet peut demander un formatage diff&eacute;rent.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Th&eacute;&acirc;tre: exemple 1">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Exemple d'image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play1.png" width="500"
          height="430" alt="Image de page de titre"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<p><tt>/*<br>
Has not his name for nought, he will be trode upon:<br>
What says my Printer now?
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; Here's your last Proof, Sir.<br>
You shall have perfect Books now in a twinkling.
</tt></p><p><tt>
&lt;i&gt;Lap.&lt;/i&gt; These marks are ugly.
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; He says, Sir, they're proper:<br>
Blows should have marks, or else they are nothing worth.
</tt></p><p><tt>
&lt;i&gt;La.&lt;/i&gt; But why a Peel-crow here?
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; I told 'em so Sir:<br>
A scare-crow had been better.
</tt></p><p><tt>
&lt;i&gt;Lap.&lt;/i&gt; How slave? look you, Sir,<br>
Did not I say, this &lt;i&gt;Whirrit&lt;/i&gt;, and this &lt;i&gt;Bob&lt;/i&gt;,<br>
Should be both &lt;i&gt;Pica Roman&lt;/i&gt;.
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; So said I, Sir, both &lt;i&gt;Picked Romans&lt;/i&gt;,<br>
And he has made 'em &lt;i&gt;Welch&lt;/i&gt; Bills,<br>
Indeed I know not what to make on 'em.
</tt></p><p><tt>
&lt;i&gt;Lap.&lt;/i&gt; Hay-day; a &lt;i&gt;Souse&lt;/i&gt;, &lt;i&gt;Italica&lt;/i&gt;?
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; Yes, that may hold, Sir,<br>
&lt;i&gt;Souse&lt;/i&gt; is a &lt;i&gt;bona roba&lt;/i&gt;, so is &lt;i&gt;Flops&lt;/i&gt; too.<br>
*/</tt></p>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Th&eacute;&acirc;tre: exemple 2">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Exemple d'image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play2.png" width="500"
          height="680" alt="Image de page de titre"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<p><tt>/*<br>
&lt;sc&gt;Clin.&lt;/sc&gt; And do I hold thee, my Antiphila,<br>
Thou only wish and comfort of my soul!<br>
<br>
&lt;sc&gt;Syrus.&lt;/sc&gt; In, in, for you have made our good man wait. (&lt;i&gt;Exeunt.&lt;/i&gt;<br>
*/<br>
<br>
<br>
<br>
<br>
ACT THE THIRD.<br>
<br>
<br>
&lt;sc&gt;Scene I.&lt;/sc&gt;<br>
<br>
/*<br>
&lt;sc&gt;Chrem.&lt;/sc&gt; 'Tis now just daybreak.--Why delay I then<br>
To call my neighbor forth, and be the first<br>
To tell him of his son's return?--The youth,<br>
I understand, would fain not have it so.<br>
But shall I, when I see this poor old man<br>
Afflict himself so grievously, by silence<br>
Rob him of such an unexpected joy,<br>
When the discov'ry can not hurt the son?<br>
No, I'll not do't; but far as in my pow'r<br>
Assist the father. As my son, I see,<br>
Ministers to th' occasions of his friend,<br>
Associated in counsels, rank, and age,<br>
So we old men should serve each other too.<br>
*/<br>
<br>
<br>
&lt;sc&gt;Scene II.&lt;/sc&gt;<br>
<br>
&lt;i&gt;Enter&lt;/i&gt; &lt;sc&gt;Menedemus.&lt;/sc&gt;<br>
<br>
/*<br>
&lt;sc&gt;Mene.&lt;/sc&gt; (&lt;i&gt;to himself&lt;/i&gt;). Sure I'm by nature form'd for misery<br>
Beyond the rest of humankind, or else<br>
'Tis a false saying, though a common one,<br>
"That time assuages grief." For ev'ry day<br>
My sorrow for the absence of my son<br>
Grows on my mind: the longer he's away,<br>
The more impatiently I wish to see him,<br>
The more pine after him.<br>
<br>
&lt;sc&gt;Chrem.&lt;/sc&gt; But he's come forth. (&lt;i&gt;Seeing&lt;/i&gt; &lt;sc&gt;Menedemus.&lt;/sc&gt;)<br>
Yonder he stands. I'll go and speak with him.<br>
Good-morrow, neighbor! I have news for you;<br>
Such news as you'll be overjoy'd to hear.<br>
*/</tt></p>
      </td>
    </tr>
  </tbody>
</table>
<br>
<a name="play3"><!-- Example --></a>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Th&eacute;&acirc;tre: exemple 3">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Exemple d'image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play3.png" width="504"
          height="206" alt="Image de th&eacute;&acirc;tre"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<p><tt>[&lt;i&gt;Hernda has come from the grove and moves up to his side&lt;/i&gt;]<br>
<br>
/*<br>
&lt;i&gt;Her.&lt;/i&gt; [&lt;i&gt;Adoringly&lt;/i&gt;] And you the master!<br>
<br>
&lt;i&gt;Hud.&lt;/i&gt; Daughter, you owe my lord Megario<br>
Some pretty thanks.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[&lt;i&gt;Kisses her cheek&lt;/i&gt;]<br>
<br>
&lt;i&gt;Her.&lt;/i&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I give them, sir.<br>
*/</tt></p>
      </td>
    </tr>
  </tbody>
</table>
<br>
<a name="play4"><!-- Example --></a>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Th&eacute;&acirc;tre: exemple 4">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Exemple d'image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play4.png" width="502"
          height="98" alt="Image de th&eacute;&acirc;tre"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<p><tt>/*<br>
&lt;i&gt;Am.&lt;/i&gt; Sure you are fasting;<br>
Or not slept well to night; some dream (&lt;i&gt;Ismena?&lt;/i&gt;)<br>
<br>
&lt;i&gt;Ism.&lt;/i&gt; My dreams are like my thoughts, honest and innocent,<br>
Yours are unhappy; who are these that coast us?<br>
You told me the walk was private.<br>
*/</tt></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="anything">Tout ce qui n&eacute;cessite &eacute;galement un traitement sp&eacute;cial, ou
   dont vous n'&ecirc;tes pas s&ucirc;r</a></h3>
<p>Si vous rencontrez quelque chose qui n'est pas couvert par ces directives et
   qui vous para&icirc;t avoir besoin d'un traitement sp&eacute;cial, ou que vous n'&ecirc;tes pas s&ucirc;r
   de quelque chose, posez votre question sur le forum du projet (en pr&eacute;cisant le
   num&eacute;ro du fichier png pour indiquer la page qui pose probl&egrave;me), 
   et ajoutez une note dans le texte
   &agrave; l'endroit qui poste probl&egrave;me. Cette note signalera le probl&egrave;me &agrave; la personne
   qui passera sur cette page ensuite, et au post-processeur.
</p>
<p>Mettez un crochet ouvrant puis deux &eacute;toiles <tt>[**</tt> avant le d&eacute;but de la note,
   et un crochet fermant apr&egrave;s <tt>]</tt> pour bien
   s&eacute;parer votre note du texte de l'auteur (n'oubliez pas les deux &eacute;toiles). Ceci
   signale au post-processeur qu'il doit s'arr&ecirc;ter et examiner ce texte et l'image
   correspondante et r&eacute;soudre le probl&egrave;me. Si vous voyez une note laiss&eacute;e
   par le volontaire qui est pass&eacute; avant vous, <b>surtout laissez-la</b>.
   Vous pouvez ajouter que vous &ecirc;tes d'accord ou pas d'accord,
   mais m&ecirc;me si vous &ecirc;tes s&ucirc;r de la solution, ne supprimez
   pas la note. Si vous avez une source qui permet
   de donner la r&eacute;ponse au probl&egrave;me, citez cette source, pour que
   le post-processeur s'y r&eacute;f&egrave;re lui aussi.
</p>
<p>Si vous r&eacute;solvez un probl&egrave;me pos&eacute; par un correcteur qui a
   laiss&eacute; une note, vous pouvez &eacute;crire un message &agrave; ce correcteur
   (en cliquant sur son nom dans l'interface de correction), pour lui expliquer comment
   g&eacute;rer la situation la prochaine fois. Mais ne supprimez jamais sa note.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="prev_notes">Notes et commentaires des correcteurs pr&eacute;c&eacute;dents</a></h3>
<p>Les notes des correcteurs pr&eacute;c&eacute;dents <b>doivent</b> &ecirc;tre gard&eacute;es.
   Vous pouvez ajouter que vous &ecirc;tes d'accord ou pas d'accord,
   mais m&ecirc;me si vous &ecirc;tes s&ucirc;r de la solution, ne supprimez
   pas la note. Si vous avez une source qui permet
   de donner la r&eacute;ponse au probl&egrave;me, citez cette source, pour que
   le post-processeur s'y r&eacute;f&egrave;re lui aussi.
</p>
<p>Si vous  r&eacute;solvez un probl&egrave;me pos&eacute; par un correcteur qui a
   laiss&eacute; une note, vous pouvez &eacute;crire un message &agrave; ce correcteur
   (en cliquant sur son nom dans l'interface de correction), pour lui expliquer comment
   g&eacute;rer la situation la prochaine fois. Mais ne supprimez jamais sa note.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<table width="100%" border="0" cellspacing="0" summary="Autres r&egrave;gles">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>

<h2><a name="sp_ency"></a>
    <a name="sp_chem"></a>
    <a name="sp_math"></a>
    <a name="sp_poet"></a>
    R&egrave;gles sp&eacute;cifiques pour livres sp&eacute;ciaux</h2>
<p>Les types de livres suivants ont des directives sp&eacute;cifiques, qui s'ajoutent
   aux pr&eacute;sentes directives ou les remplacent. Ils sont souvent plus difficiles &agrave;
   corriger, et donc recommand&eacute;s aux correcteurs exp&eacute;riment&eacute;s
   ou aux experts dans le domaine.
</p>
<p>Cliquez sur un lien pour voir les directives sur un de ces types de livres.
</p>
<ul compact>
  <li><b><a href="doc-ency.php">Encyclop&eacute;dies</a></b></li>
  <li><b><a href="doc-poet.php">Po&eacute;sie</a></b></li>
  <li><b>                       Chimie               [&agrave; compl&eacute;ter]</b></li>
  <li><b>                       Math&eacute;matiques [&agrave; compl&eacute;ter]</b></li>
</ul>
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<table width="100%" border="0" cellspacing="0" summary="Probl&egrave;mes courants">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>

<h2>Probl&egrave;mes courants</h2>


<h3><a name="bad_image">Mauvaises images</a></h3>
<p>Si une image est mauvaise (refuse de se charger, est coup&eacute;e au milieu,
   illisible), postez un message &agrave; propos de cette image dans le <a href="#forums">forum</a>
   du projet. Ne cliquez pas sur &ldquo;Return page to round&rdquo;; si vous le faites, la
   personne suivante obtiendra cette page. Cliquez plut&ocirc;t sur "Report bad
   page" pour mettre la page &agrave; part.
</p>
<p>Parfois, les images sont tr&egrave;s grosses, et votre navigateur aura des probl&egrave;mes
   pour les afficher, surtout si vous avez beaucoup de fen&ecirc;tres ouvertes ou si
   votre ordinateur est vieux. Avant de d&eacute;clarer la page "mauvaise", cliquez sur la
   ligne "image" en bas de la page pour faire appara&icirc;tre l'image sur une fen&ecirc;tre &agrave;
   part. Si l'image est alors bonne, alors le probl&egrave;me vient probablement de votre
   syst&egrave;me, ou de votre navigateur.
</p>
<p>Il est relativement courant que l'image soit bonne, mais que le texte pass&eacute; &agrave;
   l'OCR ne contienne pas la premi&egrave;re (et deuxi&egrave;me) ligne. Retapez alors les lignes
   qui manquent. Si presque toutes les lignes manquent, alors soit tapez toute la
   page (si vous voulez le faire), ou cliquez sur le bouton &ldquo;Return page to round&rdquo;
   et la page sera rendue &agrave; quelqu'un d'autre. Si plusieurs pages sont comme &ccedil;a,
   postez un message sur le <a href="#forums">forum du projet</a> pour l'indiquer au responsable du projet.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="bad_text">Image ne correspondant pas au texte</a></h3>
<p>Si l'image ne correspond pas au texte, postez un message &agrave; ce propos sur
   le <a href="#forums">forum du projet</a>. Ne cliquez pas sur &ldquo;Return page to
   round&rdquo;; si vous le faites, la personne suivante obtiendra cette page.
   Cliquez plut&ocirc;t sur "Report bad page" pour mettre la page &agrave; part.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="round1">Erreurs des correcteurs et formateurs pr&eacute;c&eacute;dents</a></h3>
<p>Si le volontaire pr&eacute;c&eacute;dent a fait beaucoup d'erreurs ou a laiss&eacute; passer un
   grand nombre de choses, vous pouvez lui envoyer un message en cliquant sur son
   nom. &Ccedil;a vous permettra de lui envoyer un message priv&eacute;: ainsi
   il corrigera mieux et fera moins d'erreurs la prochaine fois.
</p>
<p><em>Soyez aimable!</em> Ces gens sont des volontaires, essayant d'habitude de faire de leur
   mieux. Le but du message est de les informer de la mani&egrave;re correcte de corriger,
   plut&ocirc;t que de les critiquer. Donnez-leur un exemple pr&eacute;cis de ce qu'ils ont
   fait, et de ce qu'ils auraient d&ucirc; faire.
</p>
<p>Si la personne pr&eacute;c&eacute;dente a fait un travail remarquable,
   vous pouvez &eacute;galement lui envoyer un message pour le lui
   dire, surtout si elle a travaill&eacute; sur une page tr&egrave;s difficile.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="p_errors">Erreurs d'impression/d'orthographe</a></h3>
<p>Corrigez toujours les fautes introduites par l'OCR. Mais ne corrigez pas ce qui peut vous
   sembler &ecirc;tre une faute d'orthographe ou d'impression. Parfois, certains mots ne s'&eacute;crivaient pas comme
   aujourd'hui &agrave; l'&eacute;poque o&ugrave; le livre a &eacute;t&eacute; imprim&eacute;. Gardez
   l'ancienne orthographe, en particulier en ce qui concerne les accents.
</p>
<p>Si vous avez vraiment un doute, alors mettez une note dans le txte <tt>[**typo pour texte?]</tt>
   et demandez dans le forum du projet. Si vous changez vraiment quelque chose, alors mettez une
   note d&eacute;crivant ce que vous avez chang&eacute; <tt>[**corrig&eacute; "txte" en "texte"]</tt>.
   N'oubliez pas les deux &eacute;toiles <tt>**</tt> pour que le post-processeur voie le probl&egrave;me.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="f_errors">Erreurs factuelles dans le texte</a></h3>
<p>En g&eacute;n&eacute;ral, ne corrigez pas les erreurs sur les faits dans les livres.
   Beaucoup de livres que nous corrigeons d&eacute;crivent des choses que nous savons &ecirc;tre
   fausses comme &eacute;tant des faits. Laissez-les tel que l'auteur les a &eacute;crits.
</p>
<p>Une exception possible est dans les livres techniques ou scientifiques, dans
   lesquels un formule connue ou une &eacute;quation peuvent &ecirc;tre indiqu&eacute;es incorrectement.
   (En particulier si elles sont not&eacute;es d'une mani&egrave;re correcte sur d'autres pages
   du livre). Signalez cela au responsable de projet, soit en envoyant un message
   via le <a href="#forums">forum du projet</a>, ou en ins&eacute;rant une note <tt>[**sic expliquez-votre-souci]</tt>
   &agrave; cet endroit du texte.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="uncertain">Points incertains</a></h3>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [...&agrave; compl&eacute;ter...]
</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="silver" summary="Liens">
<tr>
  <td width="10">&nbsp;</td>
  <td width="100%" align="center"><font face="verdana, helvetica, sans-serif" size="1">
     Retour vers:
     <a href="..">Distributed Proofreaders home page</a>,
     &nbsp;&nbsp;&nbsp;
     <a href="faq_central.php">DP FAQ Central page</a>,
     &nbsp;&nbsp;&nbsp;
     <a href="<? echo $PG_home_url; ?>">Project Gutenberg home page</a>.
     </font>
  </td>
</tr>
</table>

<?
theme('','footer');
?>
