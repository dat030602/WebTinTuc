const handle_submit_paper = () => {
    let title =  $("input[name=title]").val();
    let abstract =  $("textarea[name=abstract]").val();
    console.log(title)
    console.log(abstract)
    if(!title || title == ''){ 
        alert("Tên bài báo không được để trống");
        return false;
    }
    if(!abstract || abstract == ''){ 
        alert("Nội dung bài báo không được để trống");
        return false;
    }
    return true;
}
