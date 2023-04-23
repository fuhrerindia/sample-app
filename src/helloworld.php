<?php
def_page([
    "render"=><<<HTML
    <div class="yugal-heading">
        <img src="$project_root/src/assets/yugal.png" alt="Yugal Logo"/>
        <h1>Yugal</h1>
        <p>
            This project is built in PHP which is further renderred to Javascript. Open `string.php` file, set DEV_MODE constant to true and start building your project. 🔥🔥
        </p>
    </div>
    HTML,
    "uri"=>"/",
    "header"=>"<title>Yugal</title>",
    "css"=><<<CSS
        body{background:black;color:white;}
        .yugal-heading{
            text-align:center;
            width: 100%;
        }
        .yugal-heading p{
            margin-top:20px;
            line-height: 30px;
            width: 60vw; 
            display: inline-block;
            background: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
        }
        .yugal-heading img{
            width: 100px;
            height: 100px;
        }
        #yugal-root{
            display:flex;
            justify-content:center;
            align-items:center;
        }
    CSS
]);
?> 