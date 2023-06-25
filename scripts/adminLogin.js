let login_data = document.querySelector('#login-form');
let login_btn = document.querySelector('#login');
let user_check = document.querySelector("#user-check");
let email_check = document.querySelector("#email-check");
let password_check = document.querySelector("#password-check");
login_btn.addEventListener('click', async (e) => {
    e.preventDefault();
    let formData = new FormData(login_data);
    let data = {};
    [...formData.entries()].forEach(element => {
        data[element[0]] = element[1];
    });
    console.log(data);
    if(data.password!==data.confPassword){
        document.getElementById("message").innerHTML="Password Doesnt Match";
    }
    else{
        document.getElementById("message").innerHTML="";
        // const adminres=fetch(adminUrl,{
        //     method:"POST",
        //     body:JSON.stringify(data),
        //     headers: {
        //                 'Content-Type': 'application/json'
        //             },
        // }).then(res=>res.json()).then(res=>console.log(res));
    }
    window.location.href="../pages/Admin.html";
    
})
