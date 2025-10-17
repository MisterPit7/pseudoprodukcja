
async function show_popup(text){
    let div = document.createElement("div");

    div.style.position="fixed";
    div.style.top = "70px";
    div.style.left="50%";
    div.style.transform="translate(-50%,0)";
    div.style.padding = "20px 25px";
    div.style.paddingTop = "10px";
    div.style.backgroundColor = "#D20A2E";
    div.style.fontSize = "16px";
    div.style.color = "white";
    div.style.zIndex = "100";
    div.style.width = "300px";
    div.style.textAlign = "center";
    div.style.opacity = "0.9";
    div.style.borderRadius = "15px";
    let h2 = document.createElement("h3");
    h2.style.color="white";
    h2.innerHTML = "ERROR!";
    div.appendChild(h2);
    let p = document.createElement("p");

    p.innerHTML = text;
    div.appendChild(p);
    
    div.addEventListener('click',()=>{
       document.querySelector('body').removeChild(div); 
    })

    document.querySelector("body").appendChild(div);

    const sleep = ms => new Promise(r => setTimeout(r, ms));

    await sleep(5000);
    div.animate([{ opacity: 0.9 }, { opacity: 0 }], {duration: 2000,iterations: 1});
    function del(){
        document.querySelector('body').removeChild(div)
    }
    setTimeout(del,2000);

}

