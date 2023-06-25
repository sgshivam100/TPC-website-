const studentData = JSON.parse(localStorage.getItem("studentData"));

console.log("data: ", (studentData));

const profile = () => {
    window.location.href = "../pages/studentProfile.html"
}
const setData = async () => {

    document.getElementById("stud-name").innerHTML = studentData.name ? studentData.name : "Not Present";
    document.getElementById("department").innerHTML = studentData.department ? studentData.department : "Not Present";
    document.getElementById("course").innerHTML = studentData.course ? studentData.course : "Not Present";
    document.getElementById("username").innerHTML = studentData.name ? studentData.name : "Not Present";
    document.getElementById("stud-profession").innerHTML = studentData.skillStack ? studentData.skillStack : "No Skills data";
    document.getElementById("contact").innerHTML = studentData.mobile ? studentData.mobile : "no Contact available";
    document.getElementById("email").innerHTML = studentData.email ? studentData.email : "No email";
    document.getElementById("cpi").innerHTML = studentData.cpi ? studentData.cpi : "No data";
    document.getElementById("roll").innerHTML = studentData.roll ? studentData.roll : "No Data";



    const img = new Image();
    img.src = studentData.profile;
    img
        .decode()
        .then(() => {
            document.getElementById("profile").src = img;
        })

    const vacancies_res = await fetch("../backend/student_portal.php?vacancies=true", {
        method: "GET",
    }).then(res => res.json());

    localStorage.setItem("vacancies", JSON.stringify(vacancies_res));

    const applied_data = await fetch("../backend/student_portal.php?applied=true", {
        method: "GET",
    });
    localStorage.setItem("applied", JSON.stringify(applied_data));

}

setData();
const editProfile = async () => {
    let studData = document.getElementById("studentData");
    studData.innerHTML =
        `<div class="row">
    <div class="col-sm-3">
        <h6 class="mb-0">Full Name</h6>
    </div>
    <div id="username" class="col-sm-9 text-secondary">
        <!-- NAME -->
        <div class="input-group mb-3">
      <input type="text" class="form-control" id="new-username" placeholder=${studentData.username} aria-label="Username" aria-describedby="basic-addon1">
    </div>
    </div>
    </div>
    <hr>
    <div class="row">
    <div class="col-sm-3">
        <h6 class="mb-0">Email</h6>
    </div>
    <div id="email" class="col-sm-9 text-secondary">
        <!-- EMAIL --><div class="input-group mb-3">
        <input type="email" class="form-control"  id="new-email" placeholder=${studentData.email} aria-label="Username" aria-describedby="basic-addon1">
      </div>
    </div>
    </div>
    <hr>
    <div class="row">
    <div class="col-sm-3">
        <h6 class="mb-0">Mobile</h6>
    </div>
    <div id="contact" class="col-sm-9 text-secondary">
        <!-- Contact Number  -->
        <div class="input-group mb-3">
      <input type="text" class="form-control" id="new-mobile" placeholder=${studentData.mobile} aria-label="Username" aria-describedby="basic-addon1">
    </div>
    </div>
    </div>
    <hr>
    <div class="row">
    <div class="col-sm-3">
        <h6 class="mb-0">Department</h6>
    </div>
    <div id="department" class="col-sm-9 text-secondary">
    <div class="input-group mb-3">
    <input type="text" class="form-control" id="new-department" placeholder=${studentData.department} aria-label="Username" aria-describedby="basic-addon1">
    </div>
    </div>
    </div>
    <hr>
    <div class="row">
    <div class="col-sm-3">
        <h6 class="mb-0">Course</h6>
    </div>
    <div id="course" class="col-sm-9 text-secondary">
    <div class="input-group mb-3">
      <input type="text" class="form-control" id="new-course" placeholder=${studentData.course} aria-label="Username" aria-describedby="basic-addon1">
    </div></div>
    </div>
    <hr>
    <div class="row">
    <div class="col-sm-3">
        <h6 class="mb-0">CPI</h6>
    </div>
    <div id="cpi" class="col-sm-9 text-secondary">
    <div class="input-group mb-3">
      <input type="text" class="form-control" id="new-cpi" placeholder=${studentData.cpi} aria-label="Username" aria-describedby="basic-addon1">
    </div></div>
    </div>
    <hr>
    <div id="cpi" class="col-sm-9 text-secondary">
    <div class="input-group mb-3">
      <input type="text" class="form-control" id="new-roll" placeholder=${studentData.roll} aria-label="Username" aria-describedby="basic-addon1">
    </div></div>
    </div>
    <hr>
    <div class="row">
    <div class="col-sm-12">
        <button class="btn btn-info " onClick="saveChanges()">Save Changes</button>
    </div>
    </div>
    `
}


const saveChanges = async () => {
    const new_data = {
        username: document.querySelector("#new-username").value,
        email: document.querySelector("#new-email").value,
        mobile: document.querySelector("#new-mobile").value,
        department: document.querySelector("#new-department").value,
        course: document.querySelector("#new-course").value,
        cpi: document.querySelector("#new-cpi").value,
        roll: document.querySelector("#new-roll").value

    }
    new_data.username != "" ? studentData["username"] = new_data["username"] : {};
    new_data.email != "" ? studentData["email"] = new_data["email"] : {};
    new_data.mobile != "" ? studentData["mobile"] = new_data["mobile"] : {};
    new_data.department != "" ? studentData["department"] = new_data["department"] : {};
    new_data.course != "" ? studentData["course"] = new_data["course"] : {};
    new_data.cpi != "" ? studentData["cpi"] = new_data["cpi"] : {};
    new_data.roll != "" ? studentData["roll"] = new_data["roll"] : {};


    //sending data to backend
    studentData["form_type"] = "update";
    const result = await fetch("backend/student_portal.php", {
        method: "POST",
        body: studentData,
        headers: {
            'Content-Type': 'application/json'
        }
    }).then(res => res.json());


    console.log(result);

    localStorage.setItem("studentData", JSON.stringify(result));



    let studData = document.getElementById("studentData");
    studData.innerHTML = `
<div class="row">
<div class="col-sm-3">
    <h6 class="mb-0">Full Name</h6>
</div>
<div id="username" class="col-sm-9 text-secondary">
    <!-- NAME -->
</div>
</div>
<hr>
<div class="row">
<div class="col-sm-3">
    <h6 class="mb-0">Email</h6>
</div>
<div id="email" class="col-sm-9 text-secondary">
    <!-- EMAIL -->
</div>
</div>
<hr>
<div class="row">
<div class="col-sm-3">
    <h6 class="mb-0">Mobile</h6>
</div>
<div id="contact" class="col-sm-9 text-secondary">
    <!-- Contact Number  -->
</div>
</div>
<hr>
<div class="row">
<div class="col-sm-3">
    <h6 class="mb-0">Department</h6>
</div>
<div id="department" class="col-sm-9 text-secondary">
    <!-- Contact Number  -->
</div>
</div>
<hr>
<div class="row">
<div class="col-sm-3">
    <h6 class="mb-0">Course</h6>
</div>
<div id="course" class="col-sm-9 text-secondary">
    <!-- Contact Number  -->
</div>
</div>
<hr>
<div class="row">
<div class="col-sm-3">
    <h6 class="mb-0">CPI</h6>
</div>
<div id="cpi" class="col-sm-9 text-secondary">
    <!-- CPI  -->
</div>
</div>
<hr>
<div class="row">
<div class="col-sm-12">
    <button class="btn btn-info "  onclick="editProfile()">Edit</button>
</div>
</div>
`
    setData();
}

const logout = async () => {
    localStorage.removeItem("studentData");
    let res = await fetch("backend/logout.php");

    window.location.href = "../Home.html";
}