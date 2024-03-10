// function showPage(pageId) {
//     // Lấy tất cả các div có class 'content-menu' và ẩn chúng
//     const allPages = document.querySelectorAll('.content-menu');
//     allPages.forEach(function(page) {
//         page.style.display = 'none';
//     });

//     // Hiển thị trang được chọn bằng cách đặt display thành 'block'
//     const selectedPage = document.querySelector(`#${pageId}`);
//     selectedPage.style.display = 'block';
// }

// 

// function showPage(pageId) {
//     // Lấy tất cả các div có class 'content-menu' và ẩn chúng
//     const allPages = document.querySelectorAll('.content-menu');
//     allPages.forEach(function(page) {
//         page.style.display = 'none';
//     });

//     // Hiển thị trang được chọn bằng cách đặt display thành 'block'
//     const selectedPage = document.getElementById(pageId);
//     selectedPage.style.display = 'block';

//     // Loại bỏ lớp button-active từ tất cả các nút
//     const allButtons = document.querySelectorAll('.menuleft-button button');
//     allButtons.forEach(function(button) {
//         button.classList.remove('button-active');
//     });

//     // Thêm lớp button-active vào nút được chọn
//     event.target.classList.add('button-active');
// }
function showPage(pageId) {
    // Lấy tất cả các div có class 'content-menu' và ẩn chúng
    const allPages = document.querySelectorAll('.content-menu');
    allPages.forEach(function(page) {
        page.style.display = 'none';
    });

    // Hiển thị trang được chọn bằng cách đặt display thành 'block'
    const selectedPage = document.getElementById(pageId);
    selectedPage.style.display = 'block';

    // Loại bỏ lớp active-button từ tất cả các nút
    const allButtons = document.querySelectorAll('.menuleft-button button span');
    allButtons.forEach(function(button) {
        button.classList.remove('active-button');
    });

    // Thêm lớp active-button vào nút được chọn
    const selectedButton = document.querySelector(`.menuleft-button button[data-page-id="${pageId}"] span`);
    selectedButton.classList.add('active-button');
}


