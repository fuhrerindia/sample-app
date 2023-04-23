<?php
include_once("./src/comp/nav.php");
def_page([
    "render" => <<<HTML
    {$navigation}
       <h1>Home</h1>
       <button onclick="page.handle()">CLICK ME</button>
        <p><span id="name"></span>! Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
            It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
            It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
            The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from de Finibus Bonorum et Malorum by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.
        </p>

    HTML,
    "header" => <<<HTML
        <title>Home &bull; Yugal</title>
    HTML,
    "didMount" => <<<JS
        ()=>{
            page.handle = () => {
                alert("Hi!");
            };
            if (localStorage.getItem("name") === null){
                page.name = prompt("What is your name?");
                localStorage.setItem("name", page.name);

            }else{
                page.name = localStorage.getItem("name");
            }
            yugal.$("#name").innerHTML = page.name;
            yugal.$("#name").style.fontWeight = "bold";
        }
    JS,
    "uri" => "/"
]);
script(<<<JS
    const universal = {};
JS);
style(<<<CSS
h1{
    margin:20px;
}
p{
    margin:20px;
    line-height:32px;
    font-size:20px;
    text-align:justify;
}
CSS);
?>