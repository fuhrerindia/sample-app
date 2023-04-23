<?php
include_once("./src/comp/nav.php");
def_page([
    "render" => <<<HTML
        {$navigation}
            <h1>Contact Us</h1>
            <section>
                <input type="text" id="uname" placeholder="Enter your name" required />
                <input type="email" id="umail" placeholder="Enter your E-Mail" required />
                <textarea id="remarks" placeholder="Remarks"></textarea>
                <button onclick="page.handleSubmission()">SUBMIT</button>
            </section>
        HTML,
    "willMount" => <<<JS
        ()=>{
            page.handleSubmission = () => {
                page.name = document.getElementById("uname").value;
                page.mail = document.getElementById("umail").value;
                page.remark = document.getElementById("remarks").value;
                fetch('./server/formaction.php', {
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        body: new URLSearchParams({
                            name: page.name,
                            mail: page.mail,
                            remark: page.remark
                        }),
                        method: "POST",
                })
                .then(r=>r.json().then(data=>{
                    if (data.status === 200){
                        alert('Form Submitted');
                        console.log(data);
                    }else{
                        alert('Server error');
                    }
                }))
                .catch(e=>console.error(e));
            }
        }
        JS,
    "uri" => "/contact-us",
    "css" => <<<CSS
        input, textarea, button{
            font-size: 18px;
        }
            input[type='text'], input[type='email']{
                display: block;
                padding: 10px;
                width: 80%;
                margin:20px;
                background: #ededed;
                border:0;
                border-radius:10px
            }
            ::placeholder{
                color:#000000;
                opacity:1;
            }
            textarea{
                width: 80%;
                height: 150px;
                background: #ededed;
                border:0;
                padding:10px;
                margin:20px;
                border-radius:18px;
            }
            button{
                margin:20px;
                width:80%;
                border:0;
                background:yellow;
                color:#000;
                padding: 10px;
                cursor:pointer;
                border-radius:18px;
                transition:0.3s;
            }
            button:hover{
                background: orange;
            }
        CSS,
    "header" => <<<HTML
            <title>Contact Us &bull; Yugal</title>
        HTML
]);
?>