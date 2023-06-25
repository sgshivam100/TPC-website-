const vacancies = JSON.parse(localStorage.getItem("vacancies"));


console.log("data: ", (vacancies));


let list = document.getElementById('vacancies');
vacancies.forEach(ele => {
    return list.innerHTML +=
        `<div class="accordion-item my-2 border rounded border-primary">
    <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <div>${ele.name}</div>
        </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
        <div class="accordion-body">
                <strong>Post:</strong>${ele.post} <br>
                <strong>Contact Here:</strong>${ele.email} <br>

                <hr>
                <strong>Total Vacancies:</strong>${ele.vacancy} <br>

                <Strong>Minimum CPI:</Strong>${ele.cpiReq}<br>
                <strong>Skill Sets:</strong>${ele.forWhom}<br>

                <strong>Minimum Salary:</strong>${ele.minsal} <br>
                <strong>Maximum Salary:</strong>${ele.maxsal} <br>
                <strong>Deadline::</strong>${ele.deadline} <br>

                <button target="_blank" onclick="apply(${ele.jobs_id})" class="btn btn-success my-2">Apply Now</button><hr>
        </div>
    </div>
    </div>`.toString()




});

 const apply= async (jobs_id)=>{
    let data_send={
        jobs_id:jobs_id,
        roll:studentData.roll
    }
    const res=await fetch(`backend/student_portal.php?job_apply=true&jobs_id=${jobs_id}`,{
        method:"POST",
        body:data_send,
        headers: {
            'Content-Type': 'application/json'
        }
    }).then(res=>res.json());

    const applied_data = await fetch("../backend/student_portal.php?applied=true", {
        method: "GET",
    });
    localStorage.setItem("applied", JSON.stringify(applied_data));

 }


const logout = async  () => {
    localStorage.removeItem("studentData");
    let res=await fetch("backend/logout.php");
    
    window.location.href = "../Home.html";
}