<?php
include 'main.php';
// Retrieve all the accounts
$stmt = $pdo->prepare('SELECT * FROM inventory');
$stmt->execute();
$inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_admin_header('Inventory', 'Inventory')?>

<h2> General Inventory</h2>

<div class="links">
    <a href="inventoryadd.php">Input New Item</a>
   
</div>

<div class="content-block">
    <div class="table">
        <table class="myTable">
            <thead>
                <tr>
                    <td>#</td>
                    <td>Hardware</td>
                    <td>Brand</td>
                    <td>Qty</td>
                    <td>Campus</td>
                    <td>Location</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($inventory)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no items</td>
                </tr>
                <?php else: ?>
                <?php foreach ($inventory as $inventory): ?>
                <tr>
                    <td><?=$inventory['id']?></td>
                    <td><?=$inventory['hardware']?></td>
                    <td><?=$inventory['brand']?></td>
                    <td><?=$inventory['qty']?></td>
                    <td><?=$inventory['campus']?></td>
                    <td><?=$inventory['classroom']?></td>
                    
                    <td>
                        <a href="inventoryadd.php?id=<?=$inventory['id']?>">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    
    function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput2");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[5];

    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'excel_data.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}
</script>

<?=template_admin_footer()?>
