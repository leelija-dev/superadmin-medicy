{/* <script> */}
        // add distributor
        const addDistributor = () => {
            var parentLocation = window.location.origin + window.location.pathname;
            $.ajax({
                url: "components/distributor-add.php",
                type: "POST",
                data: {
                    urlData: parentLocation
                },
                success: function(response) {
                    let body = document.querySelector('.add-distributor');
                    body.innerHTML = response;
                },
                error: function(error) {
                    console.error("Error: ", error);
                }
            });
        }
    
        // add manufacture
        const addManufacture = () => {
            var parentLocation = window.location.origin + window.location.pathname;
            $.ajax({
                url: "components/manufacturer-add.php",
                type: "POST",
                data: {
                    urlData: parentLocation
                },
                success: function(response) {
                    let body = document.querySelector('.add-manufacturer');
                    body.innerHTML = response;
                },
                error: function(error) {
                    console.error("Error: ", error);
                }
            });
        }
    
        // distirbutor search
        function distSearch(defaultSearch) {
            var search = document.getElementById('searchInput').value.trim();
            // distributorSearch(search);
            if (search === '') {
                distributorSearch(defaultSearch);
            } else {
                distributorSearch(search);
            }
        }
        // manufacturer search
        function manuSearch(defaultSearch) {
            var manusearch = document.getElementById('manuSearchInput').value.trim();
            if (manusearch === '') {
                manufacturerSearch(defaultSearch);
            } else {
                manufacturerSearch(manusearch);
            }
        }
        // item packet type search
        function packSearch(defaultSearch) {
            var packSearchInput = document.getElementById('packSearchInput').value.trim();
            if (packSearchInput === '') {
                packUnitSearch(defaultSearch);
            } else {
                packUnitSearch(packSearchInput);
            }
        }

        // product unit search
        function prodSearch(defaultSearch) {
            var prodSearchInput = document.getElementById('prodSearchInput').value.trim();
            // prodUnitSearch(prodSearchInput);
            if (prodSearchInput === '') {
                prodUnitSearch(defaultSearch);
            } else {
                prodUnitSearch(prodSearchInput);
            }
        }

        // find distributor function
        function findDistributor(defaultSearch) {
            var search = document.getElementById('searchInput').value || defaultSearch;
            distributorSearch(search);
        }

        function findManufacturer(defaultSearch) {
            var manusearch = document.getElementById('manuSearchInput').value || defaultSearch;
            manufacturerSearch(manusearch);
        }

        function findPackUnit(defaultSearch) {
            var packSearchInput = document.getElementById('packSearchInput').value || defaultSearch;
            packUnitSearch(packSearchInput);
        }

        function findProdUnit(defaultSearch) {
            var prodSearchInput = document.getElementById('prodSearchInput').value || defaultSearch;
            prodUnitSearch(prodSearchInput);
        }

        function distributorSearch(search) {
            $.ajax({
                url: 'ajax/distributor.list-view.ajax.php',
                type: 'POST',
                data: {
                    search: search
                },
                success: function(data) {
                    $('.DistributorModal').html(data);
                },
                error: function(error) {
                    alert('Error loading distributor modal:', error);
                }
            });
        }

        function manufacturerSearch(manusearch) {
            $.ajax({
                url: 'ajax/manufacturer.list-view.ajax.php',
                type: 'POST',
                data: {
                    search: manusearch
                },
                success: function(data) {
                    $('.ManufacturModal').html(data);
                },
                error: function(error) {
                    alert('Error loading Manufacturer modal:', error);
                }
            });
        }

        function packUnitSearch(packSearchInput) {
            $.ajax({
                url: 'ajax/packUnit.search.ajax.php',
                type: 'POST',
                data: {
                    search: packSearchInput
                },
                success: function(data) {
                    $('.PackUnitModal').html(data);
                },
                error: function(error) {
                    alert('Error loading distributor modal:', error);
                }
            });
        }

        function prodUnitSearch(prodSearchInput) {
            $.ajax({
                url: 'ajax/prodUnit.search.ajax.php',
                type: 'POST',
                data: {
                    search: prodSearchInput
                },
                success: function(data) {
                    $('.ProdUnitModal').html(data);
                },
                error: function(error) {
                    console.error('Error loading distributor modal:', error);
                }
            });
        }

        //View and Edit Manufacturer function
        // distViewAndEdit = (distributorId) => {
        //     let ViewAndEdit = distributorId;
        //     let url = "ajax/distributor.request.ajax.php?Id=" + ViewAndEdit;
        //     $(".distRequestModal").html(
        //         '<iframe width="99%" height="530px" frameborder="0" allowtransparency="true" src="' +
        //         url + '"></iframe>');
        // } 
        // end of viewAndEdit function

        // const manufactViewRequest = (manufacturerId) => {
        //     let ViewAndEdit = manufacturerId;
        //     let url = "ajax/manufacturer.request.ajax.php?Id=" + ViewAndEdit;
        //     $(".manufacturerModal").html(
        //         '<iframe width="99%" height="330px" frameborder="0" allowtransparency="true" src="' +
        //         url + '"></iframe>');
        // }
    // </script>