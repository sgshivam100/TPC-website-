
let stud_reg = document.querySelector('#company-registration');
let reg = document.querySelector('#register-company');
reg.addEventListener('click', async (e) => {
    e.preventDefault();
    let formData = new FormData(stud_reg);
    let data = {};
    [...formData.entries()].forEach(element => {
        data[element[0]] = element[1];
    });
    console.log(data);

    data["form_type"] = "company";
    if (data.name === null || data.email === null || location === null) {
        document.querySelector("#message").innerHTML = "Enter Proper Details";
    }
    else {
        if (data.password !== data.confPassword) {
            document.querySelector("#message").innerHTML = "Password Doesnt Match";
        }
        else {
            let res = await fetch("backend/Register.php", {
                method: "POST",
                body: data,

                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(res => res.json());
            document.querySelector("#message").innerHTML = res.message;

        }
    }
})
