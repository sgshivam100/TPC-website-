const applied = JSON.parse(localStorage.getItem("applied"));


console.log("data: ", (applied));


let list = document.getElementById('applied');
applied.forEach(ele => {
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
<br>
                <strong>Deadline::</strong>${ele.deadline} <br>

                <button href=${ele.applyhere} target="_blank" type="button" class="btn btn-success my-2">Apply Now</button>
                <button href=${ele.removeApplic(ele.jobs_id)} target="_blank" type="button" class="btn btn-success my-2">Apply Now</button><hr>
                
        </div>
    </div>
    </div>`.toString()




});


const removeApplic= async (jobs_id)=>{
    const res= await fetch(`backend/student_portal.php?application_remove=true&jobs_ids[0]=${jobs_id}`,{
        method:"GET",
        body:{
            jobs_ids:[jobs_id],
            roll:studentData.roll
        },
        headers: {
            'Content-Type': 'application/json'
        }
    }).then(res=>res.json()).then(async ()=>{
        const applied_data = await fetch("../backend/student_portal.php?applied=true", {
            method: "GET",
        });
        localStorage.setItem("applied", JSON.stringify(applied_data));
    
    });

    
}

const logout = async  () => {
    localStorage.removeItem("studentData");
    let res=await fetch("backend/logout.php");
    
    window.location.href = "../Home.html";
}