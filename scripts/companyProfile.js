const companyData = JSON.parse(localStorage.getItem("companyData"));
console.log("data: ", (companyData));

const profile = () => {
    window.location.href = "../pages/companyProfile.html"
}

const appliedStuds = async () => {
    let res = await fetch(url).then(res => res.json());
    return res;

}

const setData = () => {
    document.title = "TPC- " + companyData.username;
    document.getElementById("company_name").innerHTML = companyData.name ? companyData.name : "Not Present";
    document.getElementById("id").innerHTML = companyData.id ? companyData.id : "Not Present";
    document.getElementById("companyType").innerHTML = companyData.companyType ? companyData.companyType : "Not Present";
    document.getElementById("email").innerHTML = companyData.email ? companyData.email : "No email";
    document.getElementById("companyName").innerHTML = companyData.name ? companyData.name : "Not Present";
    document.getElementById("company_type").innerHTML = companyData.companyType ? companyData.companyType : "Not Present";
    document.getElementById("company_main").innerHTML = companyData.name ? companyData.name : "Not Present";

    const img = new Image();
    img.src = companyData.logo;
    img
        .decode()
        .then(() => {
            document.getElementById("profile").src = img;
        })

    companyData["appliedStuds"] = appliedStuds;
    let interns = document.getElementById("internsList");
    companyData.appliedStuds.forEach(ele => {
        return interns.innerHTML += `  <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
            ${ele.name}
          </button>
        </h2>
        <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
                  <strong>${ele.role}</strong><br>
                  <strong>${ele.email}</strong><br>
                  <strong>${ele.cpi}</strong><br>
                  <strong>${ele.department}</strong>

          </div>
        </div>
      </div>`
    });
}

setData();
const editProfile = () => {
    let currData = document.getElementById("companyData");
    currData.innerHTML = `
    <div class="row">
    <div class="col-sm-3">
        <h6 class="mb-0">Company Name</h6>
    </div>
    <div id="company_name" class="col-sm-9 text-secondary">
        <!-- NAME -->
        <input type="text" class="form-control" id="new-username" placeholder=${companyData.username} aria-label="Username" aria-describedby="basic-addon1">

    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-3">
        <h6 class="mb-0">Contact Email</h6>
    </div>
    <div id="email" class="col-sm-9 text-secondary">
        <!-- EMAIL -->      <input type="email" class="form-control" id="new-email" placeholder=${companyData.email} aria-label="Username" aria-describedby="basic-addon1">

    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-3">
        <h6 class="mb-0">Company ID</h6>
    </div>
    <div id="id" class="col-sm-9 text-secondary">
    <input type="text" class="form-control" id="new-id" disabled placeholder=${companyData.id} aria-label="Username" aria-describedby="basic-addon1">
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-3">
        <h6 class="mb-0">Company Type</h6>
    </div>
    <div id="company_type" class="col-sm-9 text-secondary">
        <input type="text" class="form-control" id="new-companyType" placeholder=${companyData.companyType} aria-label="Username" aria-describedby="basic-addon1">

    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-12">
        <button class="btn btn-info " onclick="saveChanges()" >Save Changes</button>
    </div>
</div>

    `

}


const saveChanges = async () => {

    const new_data = {
        username: document.querySelector("#new-username").value,
        email: document.querySelector("#new-email").value,
        companyType: document.querySelector("#new-companyType").value,
    }
    new_data.username != "" ? companyData["username"] = new_data["username"] : {};
    new_data.email != "" ? companyData["email"] = new_data["email"] : {};
    new_data.companyType != "" ? companyData["companyType"] = new_data["companyType"] : {};
    console.log(companyData);

    localStorage.setItem("companyData", JSON.stringify(companyData));


    //sending data to backend
    // let result = await fetch(updateCompanyUrl, {
    //     method: 'PUT',
    //     headers: {
    //         'Content-Type': 'application/json'
    //     },
    //     body: JSON.stringify(new_data)
    // })
    //     .then(response => response.json())
    //     .then(data => {

    //        console.log(data);
    //     })
    //     .catch(error => {
    //         console.error('Error updating user details:', error);
    //     });





    let currData = document.getElementById("companyData");
    currData.innerHTML = `
 <div class="row">
 <div class="col-sm-3">
     <h6 class="mb-0">Company Name</h6>
 </div>
 <div id="company_name" class="col-sm-9 text-secondary">
     <!-- NAME -->${companyData.username}
 </div>
</div>
<hr>
<div class="row">
 <div class="col-sm-3">
     <h6 class="mb-0">Contact Email</h6>
 </div>
 <div id="email" class="col-sm-9 text-secondary">
     <!-- EMAIL -->
     ${companyData.email}
 </div>
</div>
<hr>
<div class="row">
 <div class="col-sm-3">
     <h6 class="mb-0">Company ID</h6>
 </div>
 <div id="id" class="col-sm-9 text-secondary">
<!-- ID -->
${companyData.id}
         </div>
</div>
<hr>
<div class="row">
 <div class="col-sm-3">
     <h6 class="mb-0">Company Type</h6>
 </div>
 <div id="company_type" class="col-sm-9 text-secondary">
    ${companyData.companyType}
 </div>
</div>
<hr>

<div class="row">
 <div class="col-sm-12">
     <button class="btn btn-info " onclick="editProfile()" >Edit</button>
 </div>
</div>
</div>
 `
    setData();
}




const logout = () => {
    localStorage.removeItem("companyData");
    window.location.href = "../Home.html";
}