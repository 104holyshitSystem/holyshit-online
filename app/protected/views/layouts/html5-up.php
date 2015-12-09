<!DOCTYPE HTML>
<!--
	Spectral by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
<head>
    <title>Holy Shit</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--[if lte IE 8]><script src="<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/assets/css/main.css" />
    <!--[if lte IE 8]><link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/assets/css/ie8.css" /><![endif]-->
    <!--[if lte IE 9]><link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/assets/css/ie9.css" /><![endif]-->

    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/assets/js/jquery.min.js"></script>
</head>
<body class="<?php echo $this->bodyClass; ?>">

<!-- Page Wrapper -->
<div id="page-wrapper">

    <!-- Header -->
    <header id="header" class="<?php echo $this->headerClass; ?>">
        <h1><a href="<?php echo Yii::app()->request->baseUrl; ?>">Holy Shit</a></h1>
        <nav id="nav">
            <ul>
                <li class="special">
                    <a href="#menu" class="menuToggle"><span>Menu</span></a>
                    <div id="menu">
                        <ul>
                            <li><a href="<?php echo Yii::app()->createUrl('/toilet/introduction/'); ?>">HOME</a></li>
                            <li><a href="<?php echo Yii::app()->createUrl('/toilet/live/'); ?>">LIVE</a></li>
                            <li><a href="#">Sign Up</a></li>
                            <li><a href="#">Log In</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <?php echo $content; ?>

    <!-- Footer -->
    <footer id="footer">
        <ul class="icons">
            <li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
            <li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
            <li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
            <li><a href="#" class="icon fa-dribbble"><span class="label">Dribbble</span></a></li>
            <li><a href="#" class="icon fa-envelope-o"><span class="label">Email</span></a></li>
        </ul>
        <ul class="copyright">
            <li>&copy; Untitled</li><li>Design: Holy Shit</li>
        </ul>
    </footer>

</div>

<!-- Scripts -->

<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/assets/js/jquery.scrollex.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/assets/js/jquery.scrolly.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/assets/js/skel.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/assets/js/util.js"></script>
<!--[if lte IE 8]><script src="<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/assets/js/ie/respond.min.js"></script><![endif]-->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/assets/js/main.js"></script>

</body>
</html>
