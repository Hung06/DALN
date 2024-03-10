    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.acc-dow').addEventListener('click', function() {
            var userDrop = document.querySelector('.user-drop');
            userDrop.style.display = (userDrop.style.display === 'block') ? 'none' : 'block';
        });
    });

      // Timf kieems
    document.addEventListener('DOMContentLoaded', function() {
      const searchForm = document.getElementById('searchForm');
      const searchInput = document.getElementById('searchInput');
      const searchIcon = document.getElementById('searchIcon');
  
      // Sự kiện nghe khi form được submit
      searchForm.addEventListener('submit', function(event) {
          event.preventDefault(); // Ngăn chặn việc submit form
          searchCLBs();
      });
  
      // Sự kiện nghe khi icon tìm kiếm được click
      searchIcon.addEventListener('click', function() {
          searchCLBs();
      });
  
      // Hàm thực hiện tìm kiếm CLBs
      function searchCLBs() {
          const searchTerm = searchInput.value.trim();
  
          // Nếu từ khóa tìm kiếm không rỗng
          if (searchTerm !== '') {
              // Redirect đến trang search.php với từ khóa tìm kiếm
              window.location.href = `Search.php?searchTerm=${encodeURIComponent(searchTerm)}`;
          }
      }
  });
  function showClubTable() {
    var clubTable = document.getElementById("table-club");
    var eventTable = document.getElementById("table-event");

    clubTable.style.display = "table";
    eventTable.style.display = "none";
}

// Định nghĩa hàm showEventTable
function showEventTable() {
    var clubTable = document.getElementById("table-club");
    var eventTable = document.getElementById("table-event");

    clubTable.style.display = "none";
    eventTable.style.display = "table";
}



  
  
    
    
    