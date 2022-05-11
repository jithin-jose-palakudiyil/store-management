<div class="table-responsive" style="max-height: 280px;">
    <table class="table table-bordered table-framed">
        <thead>
            <tr>
            <th>Date</th>
            <th>Comments</th> 
            <th>Status</th> 
            <th>Completion Date</th>
            </tr>
        </thead>
        <tbody>
             <?php if (isset($ListModel) && $ListModel):
                echo $ListModel;
             else: ?>
            <tr>
                <td colspan="3">
                    Sorry, nothing found !
                </td>
            </tr> 
            <?php endif;  ?>
        </tbody>
    </table>
</div>