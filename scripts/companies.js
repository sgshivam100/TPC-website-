const userData = JSON.parse(localStorage.getItem("userData"));

console.log("data: ", (userData));
let companies = document.getElementById("companies");
userData.companies.forEach(ele => {
    return companies.innerHTML += `<div class="col my-2 ">
        <div class="card border border-info rounded" style="width: 18rem;">
        <img src=${ele.img} class="card-img-top" alt="..." height="150">
        <div class="card-body">
            <h5 class="card-title">${ele.name}</h5>
            <p class="card-text">${ele.description} </p>
            <a href=${ele.visit} target="_blank" class="btn btn-primary">Visit Official Site</a>
        </div>
    </div>
    
    </div>
    `
});




const logout=()=>{
    localStorage.removeItem("userData");
    window.location.href="../Home.html";
}