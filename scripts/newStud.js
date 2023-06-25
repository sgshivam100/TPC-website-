let stud_reg = document.querySelector('#student-registration');
let reg = document.querySelector('#register-stud');
reg.addEventListener('click', async (e) => {
    e.preventDefault();
    let formData = new FormData(stud_reg);
    let data = {};
    [...formData.entries()].forEach(element => {
        data[element[0]] = element[1];
    });
    data["form_type"] = "student";
    console.log(data);

    if (data.roll === null || data.department === null || data.course === null || data.dob === null || data.cpi === null) {
        document.getElementById("message").innerHTML = "Enter Correct Details";
    }

    else {
        if (data.password === data.confPassword) {
            const res = await fetch("backend/Register.php", {
                method: "POST",
                body: data,
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(res => res.json());
            document.getElementById("message").innerHTML = res.message;

        }
        else {
            document.getElementById("message").innerHTML = "Passwords Doesn't Match ";

        }
    }



})
