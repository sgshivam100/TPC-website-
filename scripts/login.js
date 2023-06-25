let login_data = document.querySelector('#login-form');
let login_btn = document.querySelector('#login');
let user_check = document.querySelector("#user-check");
let password_check = document.querySelector("#password-check");
login_btn.addEventListener('click', async (e) => {
    e.preventDefault();
    let formData = new FormData(login_data);
    let data = {};
    [...formData.entries()].forEach(element => {
        data[element[0]] = element[1];
    });



    if (data.id === "") {
        user_check.style.color = "red";
    }
    else {
        user_check.style.color = "white";


        if (data.password === "") {
            password_check.style.color = "red"
        }
        else {
            password_check.style.color = "white"


            if (data.user_type === "student") {


                const result = await fetch("../backend/login.php", {
                    method: "POST",
                    body: {
                        roll: data.id,
                        password: data.password,
                        form_type: "student"
                    },
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).then(res => res.json());

                if (result.status === "failed") {
                    document.querySelector("#message").innerHTML = result.message;
                }
                else {
                    document.querySelector("#message").innerHTML = "";

                    localStorage.setItem("studentData", JSON.stringify(result));
                    window.location.href = "/pages/studentProfile.html";
                }
            }
            else {
                const result = await fetch("../backend/login.php", {
                    method: "POST",
                    body: {
                        email: data.id,
                        password: data.password,
                        form_type: "company"
                    },
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).then(res => res.json());
                 
                if (result.status === "Failed") {
                    document.querySelector("#message").innerHTML = result.message;
                } else {
                    document.querySelector("#message").innerHTML = "";
                    localStorage.setItem("companyData", JSON.stringify(result));

                    window.location.href = "/pages/companyProfile.html";
                }
            }

        }

    }
})
