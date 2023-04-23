<?php
    $navigation = <<<HTML
    <ul class="nav">
        <li><a href="./">Home</a></li>
        <li><a href="./about-us">About Us</a></li>
        <li><a href="./contact-us">Contact Us</a></li>
    </ul>
    HTML;
    style(<<<CSS
        .nav{
            width:100%;
            background:green;
            display:flex;
            list-style:none;
        }
        .nav li{
            padding: 10px;
            margin-left:20px;
            color: #fff;
            transition:0.3s;
        }
        .nav a{
            color:inherit;
            text-decoration:none;
            font-size:20px;
        }
        .nav li:hover{
            background: yellow;
            color:#000;
        }
    CSS);
?>