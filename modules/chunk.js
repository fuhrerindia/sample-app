    yugal.page({render: `<div class="yugal-heading">\n    <img src="http://localhost/d/passwords/src/assets/yugal.png" alt="Yugal Logo"/>\n    <h1>Yugal</h1>\n    <p>\n        This project is built in PHP which is further renderred to Javascript. Open \`string.php\` file, set DEV_MODE constant to true and start building your project. 🔥🔥\n    </p>\n</div>`,uri: `/`,css: `    body{background:black;color:white;}    .yugal-heading{        text-align:center;        width: 100%;    }    .yugal-heading p{        margin-top:20px;        line-height: 30px;        width: 60vw;         display: inline-block;        background: #1e1e1e;        padding: 20px;        border-radius: 10px;    }    .yugal-heading img{        width: 100px;        height: 100px;    }    #yugal-root{        display:flex;        justify-content:center;        align-items:center;    }`,header: `<title data-yugal>Yugal</title>`,willMount: ()=>{},didMount: ()=>{},didUnMount: ()=>{},willUnMount: ()=>{}});
    yugal.page({render: `<ul class="nav">\n    <li><a href="./">Home</a></li>\n    <li><a href="./about-us">About Us</a></li>\n    <li><a href="./contact-us">Contact Us</a></li>\n</ul>\n   <h1>Home</h1>\n   <button onclick="page.handle()">CLICK ME</button>\n    <p><span id="name"></span>! Lorem Ipsum is simply dummy text of the printing and typesetting industry.\n        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.\n        It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.\n        It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.\n        The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from de Finibus Bonorum et Malorum by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.\n    </p>\n`,uri: `/`,css: ``,header: `    <title data-yugal>Home &bull; Yugal</title>`,willMount: ()=>{},didMount:     ()=>{
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
    },didUnMount: ()=>{},willUnMount: ()=>{}});
    const universal = {};
    yugal.error404({render: `<ul class="nav">\n    <li><a href="./">Home</a></li>\n    <li><a href="./about-us">About Us</a></li>\n    <li><a href="./contact-us">Contact Us</a></li>\n</ul>\n<center>\n    <h1>ERROR 404</h1>\n    <p>Page Not Found</p>\n</center>`,css: ``,header: `    <title>
        Page Not Found &bull; Yugal
    </title>`,willMount: ()=>{},didMount: ()=>{},didUnMount: ()=>{},willUnMount: ()=>{}});
    yugal.page({render: `    <ul class="nav">\n    <li><a href="./">Home</a></li>\n    <li><a href="./about-us">About Us</a></li>\n    <li><a href="./contact-us">Contact Us</a></li>\n</ul>\n    <h1>About Us</h1>\n    <p>\n    I have chosen the WP-PageNavi plugin (the most popular option) to demonstrate the installation and setup in this blog. Almost all WordPress pagination plugins are installed and configured the same way, so you can adopt this method to install your preferred plugin.\n    If you still don’t understand the importance of pagination, then look at Google, and notice how it treats its audience with the search results. Does it include all the results on a single page or separate them with various pages? Take notes. Shall you require more assistance? Feel free to drop your queries in the comment section below.\n    </p>`,uri: `/about-us`,css: ``,header: `    <title data-yugal>About Us &bull; Yugal</title>`,willMount: ()=>{},didMount: ()=>{},didUnMount: ()=>{},willUnMount: ()=>{}});
    yugal.page({render: `<ul class="nav">\n    <li><a href="./">Home</a></li>\n    <li><a href="./about-us">About Us</a></li>\n    <li><a href="./contact-us">Contact Us</a></li>\n</ul>\n    <h1>Contact Us</h1>\n    <section>\n        <input type="text" id="uname" placeholder="Enter your name" required />\n        <input type="email" id="umail" placeholder="Enter your E-Mail" required />\n        <textarea id="remarks" placeholder="Remarks"></textarea>\n        <button onclick="page.handleSubmission()">SUBMIT</button>\n    </section>`,uri: `/contact-us`,css: `input, textarea, button{    font-size: 18px;}    input[type='text'], input[type='email']{        display: block;        padding: 10px;        width: 80%;        margin:20px;        background: #ededed;        border:0;        border-radius:10px    }    ::placeholder{        color:#000000;        opacity:1;    }    textarea{        width: 80%;        height: 150px;        background: #ededed;        border:0;        padding:10px;        margin:20px;        border-radius:18px;    }    button{        margin:20px;        width:80%;        border:0;        background:yellow;        color:#000;        padding: 10px;        cursor:pointer;        border-radius:18px;        transition:0.3s;    }    button:hover{        background: orange;    }`,header: `    <title data-yugal>Contact Us &bull; Yugal</title>`,willMount: ()=>{
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
},didMount: ()=>{},didUnMount: ()=>{},willUnMount: ()=>{}});
    yugal.projectRoot = "http://localhost/d/passwords";
yugal.backend = true; 
