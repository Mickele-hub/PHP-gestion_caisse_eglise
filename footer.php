<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init();
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#mytable').DataTable(
            {
                "oLanguage":{
                    "sLengthMenu": "Afficher _MENU_ Enregistrements",
                    "sSearch": "Rechercher :",
                    "sInfo": "Total de _TOTAL_ enregistrements (_END_ / _TOTAL_)",
                    "oPaginate":{
                        "sNext": "Suivant",
                        "sPrevious":"Précédent"
                    }
                }
            }
        );
    });
</script>
