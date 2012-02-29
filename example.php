<?php include("categorizr/categorizr.php");?>
<!DOCTYPE html>
<head>
<meta charset="utf-8" />
<title>Categorizr Example</title>
<style type="text/css">
	body{font:14px/1.5 Arial, Helvetica, sans-serif;}
	.not{color:#f00;}
</style>
</head>

<body>


<!--
You can load anything you want in between if statments, be it markup, styles, scripts, etc.
With this, you can conditonally load resources based on the cateogry the device falls into.
-->

<?php if (isMobile()) { ?>
This device is a mobile
<br />
<?php } ?>

<?php if (isTablet()) { ?>
This device is tablet
<br />
<?php } ?>

<?php if (isDesktop()) { ?>
This device is a desktop
<br />
<?php } ?>

<?php if (isTV()) { ?>
This device is a TV
<br />
<?php } ?>


<?php if (!isMobile()) { ?>
This device is <span class="not">not</span> a mobile
<br />
<?php } ?>

<?php if (!isTablet()) { ?>
This device is <span class="not">not</span> a tablet
<br />
<?php } ?>

<?php if (!isDesktop()) { ?>
This device is <span class="not">not</span> a desktop
<br />
<?php } ?>

<?php if (!isTV()) { ?>
This device is <span class="not">not</span> a tv
<br />
<?php } ?>

<!-- 
You can change how the device is categorized by adding a link with a view variable in it.
Options include, desktop, tablet, tv, or mobile.
-->

<h2>Toggle current device category</h2>

<ul>
	<li><a href="?view=desktop">View Desktop Version</a></li>
	<li><a href="?view=mobile">View Mobile Version</a></li>
</ul>

</body>
</html>
