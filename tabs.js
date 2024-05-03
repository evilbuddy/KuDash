function opengroup(groupname)
{
    [].forEach.call(document.getElementsByClassName("group-btn"), (button) => {
        button.classList.remove("btn-active");
    });

    [].forEach.call(document.getElementsByClassName("group-div"), (button) => {
        button.classList.remove("div-active");
    });

    document.getElementById("btn-" + groupname).classList.add("btn-active");
    document.getElementById("grp-" + groupname).classList.add("div-active");
}