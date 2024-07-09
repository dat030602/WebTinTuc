<?php
session_start();
?>
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
<header class="p-3 mb-3 border-bottom">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
          <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
        </a>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><a href="/index.php" class="nav-link px-2 link-secondary">Home</a></li>
        </ul>

        <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" id="searchForm">
          <input type="search" class="form-control" id="searchInput" placeholder="Search..." aria-label="Search" name="q">
        </form>
        <?php 
            if (isset($_SESSION['account'])){
                $user_id = $_SESSION['account']['user_id'];
                echo "
                    <div class='dropdown text-end'>
                    <a href='#' class='d-block link-dark text-decoration-none dropdown-toggle' id='dropdownUser1' data-bs-toggle='dropdown' aria-expanded='false'>
                        <img src='https://github.com/mdo.png' alt='mdo' width='32' height='32' class='rounded-circle'>
                    </a>
                    <ul class='dropdown-menu text-small' aria-labelledby='dropdownUser1' style=''>
                        <li><a class='dropdown-item' href='../pages/profile.php?id=$user_id'>Profile</a></li>
                        <li><hr class='dropdown-divider'></li>
                        <li><a class='dropdown-item' href='../pages/logout.php'>Sign out</a></li>
                    </ul>
                    </div>
                ";
            }
            else
              echo "<a href='../pages/login.php' class='btn btn-primary'>Login</a>";
        ?>
        
      </div>
    </div>
  </header>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
      $(document).ready(function() {
          $('#searchForm').on('submit', function(e) {
              e.preventDefault();
              var query = $('#searchInput').val();
              $.ajax({
                  type: 'GET',
                  url: `/search.php`,
                  data: { q: query },
                  dataType: 'json',
                  success: function(response) {
                      if (response.status == 'success') {
                        window.location.href = response.link
                      }
                      else{
                          alert('Error: ' + response.message);
                      }
                  },
                  error: function(xhr, status, error) {
                      console.error('AJAX Error: ' + status, error);
                  }
              });
          });
      });
</script>