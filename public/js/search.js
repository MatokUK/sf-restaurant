var restaurantAjax = {
    search: function() {
        var searchForm = document.getElementById('search_form');
        var searchTerm = searchForm.elements['search_restaurant[search]'].value;
        var includeClosed = ~~searchForm.elements['search_restaurant[closed]'].checked;
        var spinner = document.getElementsByClassName('js-search-spinner')[0];
        spinner.style.display = 'block';

        var xhr = new XMLHttpRequest();
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                var responseData = JSON.parse(xhr.response);
                document.getElementById('restaurant-table').innerHTML = responseData;
            } else {
                console.log('The request failed!');
            }

            window.history.pushState('search-result', null, '?s=' + searchTerm + '&closed=' + includeClosed);
            spinner.style.display = 'none';
        };

        xhr.open('GET', '/search?s=' + searchTerm + '&closed=' + includeClosed);
        xhr.send();
    }
};


var restaurantSearch = {
    searchTerm: null,
    includeClosed: null,

    init: function () {
          var searchForm = document.getElementById('search_form');
          this.searchTerm = searchForm.elements['search_restaurant[search]'];
          this.includeClosed = searchForm.elements['search_restaurant[closed]'];

          this.searchTerm.addEventListener('keyup', function(e) {
              if (e.target.value.length > 1) {
                restaurantAjax.search();
              }
          });

          this.includeClosed.addEventListener('change', function (e) {
              restaurantAjax.search();
          });
    }
};

restaurantSearch.init();
