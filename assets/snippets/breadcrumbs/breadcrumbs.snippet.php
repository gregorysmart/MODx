<?php
/* --------------------
 * Snippet: Breadcrumbs
 * --------------------
 * Version: 0.9d
 * Date: 2006.06.12
 * jaredc@honeydewdesign.com
 * Honorable mentions:
 * - Bill Wilson
 * - wendy@djamoer.net
 * - grad
 *
 * This snippet was designed to show the path through the various levels of site structure
 * back to the root. It is NOT necessarily the path the user took to arrive at a given
 * page.
 */

/** Configuration Settings */

/* @var integer $maxCrumbs Max number of elements to have in a path.
 * 100 is an arbitrary high number.
 * If you make it smaller, like say 2, but you are 5 levels deep, it will appear as:
 * Home > ... > Level 4 > Level 5
 * It should be noted that "Home" and the current page do not count. Each of these are
 * configured separately.
 */
(isset($maxCrumbs)) ? $maxCrumbs : $maxCrumbs = 100;

/* @var boolean $pathThruUnPub When your path includes an unpublished folder, setting this to
 * 1 will show all documents in path EXCEPT the unpublished.
 * Example path (unpublished in caps): home > news > CURRENT > SPORTS > skiiing > article
 * $pathThruUnPub = true would give you this: home > news > skiiing > article
 * $pathThruUnPub = false would give you this: home > skiiing > article (assuming you have
 * home crumb turned on)
 */
(isset($pathThruUnPub)) ? $pathThruUnPub : $pathThruUnPub = 1;

/* @var boolean $respectHidemenu Setting this to 1 will hide items in the breadcrumb list
 * that are set to be hidden in menus.
 */
(isset($respectHidemenu)) ? $respectHidemenu : $respectHidemenu = 1;

/**
 * @var boolean $showHomeCrumb Would you like your crumb string to start with a link to
 * home? Some would not because a home link is usually found in the site logo or elsewhere
 * in the navigation scheme.
 */
(isset($showHomeCrumb)) ? $showHomeCrumb : $showHomeCrumb = 1;

/**
 * @var boolean $showCrumbsAtHome You can use this to turn off the breadcrumbs on the home
 * page with 1. grad: actually '1' shows and '0' hides crumbs at homepage
 */
(isset($showCrumbsAtHome)) ? $showCrumbsAtHome : $showCrumbsAtHome = 0;

/**
 * @var boolean $showCurrentCrumb Show the current page in path with 1 or not with 0.
 */
(isset($showCurrentCrumb)) ? $showCurrentCrumb : $showCurrentCrumb = 1;

/**
 * @var boolean $currentAsLink If you want the current page crumb to be a link (to itself)
 * then set to 1.
 */
(isset($currentAsLink)) ? $currentAsLink : $currentAsLink = 0;

/**
 * @var string $crumbSeparator Define what you want between the crumbs
 */
(isset($crumbSeparator)) ? $crumbSeparator : $crumbSeparator = "&raquo;";

/**
 * @var string $homeCrumbTitle Just in case you want to have a home link, but want to call
 * it something else
 */
(isset($homeCrumbTitle)) ? $homeCrumbTitle : $homeCrumbTitle = 'Home';

/**
 * @var string $homeCrumbDescription In case you want to have a custom description of the
 * home link. Defaults to title of home link
 */
(isset($homeCrumbDescription)) ? $homeCrumbDescription : $homeCrumbDescription = $homeCrumbTitle;

/**
 * @var string $titleField To change default page field to be used as a breadcrumb title,
 * default is pagetitle
 */
(isset($titleField)) ? $titleField : $titleField = 'pagetitle';

/**
 * @var string $descField To change default page field to be used as a breadcrumb
 * description, default is description (GA: falls back to pagetitle if description is
 * empty)
 */
(isset($descField)) ? $descField : $descField = 'description';

/**
 * Included classes
 * .B_crumbBox Span that surrounds all crumb output
 * .B_hideCrumb Span surrounding the "..." if there are more crumbs than will be shown
 * .B_currentCrumb Span or A tag surrounding the current crumb
 * .B_firstCrumb Span that always surrounds the first crumb, whether it is "home" or not
 * .B_lastCrumb Span surrounding last crumb, whether it is the current page or not .
 * .B_crumb Class given to each A tag surrounding the intermediate crumbs (not home, or
 * hide)
 * .B_homeCrumb Class given to the home crumb
 */


/***************************************
 * END CONFIG SETTINGS
 * THE REST SHOULD TAKE CARE OF ITSELF
 ***************************************/

// Check for home page
$path = $modx->config['assets_path'].'snippets/breadcrumbs/';
$modx->loadClass('breadcrumbs',$path,true,true);
$BreadCrumbs = new BreadCrumbs($modx,array(
	'maxCrumbs' => isset($maxCrumbs) ? $maxCrumbs : 100,
	'pathThruUnPub' => isset($pathThruUnPub) ? $pathThruUnPub : true,
	'respectHidemenu' => isset($respectHidemenu) ? $respectHidemenu : true,
	'showHomeCrumb' => isset($showHomeCrumb) ? $showHomeCrumb : true,
	'showCrumbsAtHome' => isset($showCrumbsAtHome) ? $showCrumbsAtHome : false,
	'showCurrentCrumb' => isset($showCurrentCrumb) ? $showCurrentCrumb : true,
	'currentAsLink' => isset($currentAsLink) ? $currentAsLink : false,
	'crumbSeparator' => isset($crumbSeparator) ? $crumbSeparator : "&raquo;",
	'homeCrumbTitle' => isset($homeCrumbTitle) ? $homeCrumbTitle : 'Home',
	'homeCrumbDescription' => isset($homeCrumbDescription) ? $homeCrumbDescription : '',
	'titleField' => isset($titleField) ? $titleField : 'pagetitle',
	'descField' => isset($descField) ? $descField : 'description',
));

return $BreadCrumbs->load();