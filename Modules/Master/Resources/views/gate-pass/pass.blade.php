<!DOCTYPE html>
<html lang="en" style="box-sizing: border-box;">
    <head style="box-sizing: border-box;">
        <meta charset="utf-8" style="box-sizing: border-box;">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" style="box-sizing: border-box;">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" style="box-sizing: border-box;">
        <title style="box-sizing: border-box;">GATE PASS</title>  
        <script type="text/javascript">
        window.print();
        </script>
    </head>
    <body style="box-sizing: border-box;color: #000000;font-weight: 400;font-family: 'Lato', sans-serif;">
        <?php
        $breakage = \Modules\Master\Entities\Breakage::select('store.name as store_name','breakage.item_id','breakage.id','pivot_store_items.id as pivot_id','breakage.step','breakage.breakage_date','items.name','batch_items.unique_id')
                        ->join('items','items.id','=', 'breakage.item_id')
                        ->join('pivot_store_items','pivot_store_items.id','=', 'breakage.pivot_store_item_id')
                ->join('store','store.id','=', 'breakage.store_id')
                        ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id')->where('breakage.id',$GatePass->breakage_id)->first(); 
       
         
//dd($breakage);

// dd($GatePass);
        ?>
        <!-- cash-area-start -->
            <div class="cash-area" style="box-sizing: border-box;max-width: 1000px;margin: auto;margin-top: 50px;margin-bottom: 50px;border-radius: 22px;box-shadow: 0 0 20px #00000024;padding: 50px 80px;">
                <div class="cash-header" style="box-sizing: border-box;display: flex;align-items: center;justify-content: space-between;margin-bottom: 88px;">
                    <div class="cash-logo" style="box-sizing: border-box;">
                        <a href="#" style="box-sizing: border-box;"><img src="{{asset('public/assets/images/site-logo.png')}}" alt="" style="box-sizing: border-box;max-width: 100%;height: auto;"></a>
                    </div>
                    <div class="cash-header-text" style="box-sizing: border-box;">
                        <h3 style="box-sizing: border-box;margin: 0 0 15px;color: #000000;font-family: 'Lato', sans-serif;font-size: 29px;text-transform: uppercase;font-weight: 800;margin-bottom: 4px;">GATE PASS</h3>
                        <p style="box-sizing: border-box;text-align: right;text-transform: capitalize;font-size: 14px;">date: <?=$GatePass->pass_date?></p>
                        <p style="box-sizing: border-box;text-align: right;text-transform: capitalize;font-size: 14px;">PID: <?=$GatePass->id?></p>
                    </div>
                </div>
                <div class="cash-boxy" style="box-sizing: border-box;">
                    <div class="cash-top-containt" style="box-sizing: border-box;margin-bottom: 10px;">
                        <p style="box-sizing: border-box;font-size: 16px;line-height: 33px;">Mr <input type="text" value="<?=$GatePass->name?>"style="box-sizing: border-box;border: 0;border-bottom: 1px solid #000;height: 23px;text-align: center;">
                            <!--Form <input type="text" style="box-sizing: border-box;border: 0;border-bottom: 1px solid #000;height: 23px;text-align: center;">-->
                            is allowed to <br style="box-sizing: border-box;display: none;">
                            take the following items form the <input type="text" value="<?=$breakage->store_name?>" style="box-sizing: border-box;border: 0;border-bottom: 1px solid #000;height: 23px;text-align: center;">
                            
                        of service.
                        </p>
                    </div>
                    <div class="cash-table" style="box-sizing: border-box;margin-bottom: 100px;">
                        <table class="table" style="box-sizing: border-box;border-collapse: collapse;width: 100%;max-width: 100%;margin-bottom: 1rem;background-color: transparent;">
                            <thead style="box-sizing: border-box;">
                              <tr style="box-sizing: border-box;">
                                <th scope="col" style="box-sizing: border-box;text-align: center;border: 1px solid #000 !important;padding: .75rem;vertical-align: top;border-top: 1px solid #dee2e6;">SI.No</th>
                                <th scope="col" style="box-sizing: border-box;text-align: center;border: 1px solid #000 !important;padding: .75rem;vertical-align: top;border-top: 1px solid #dee2e6;">Item name</th>
                                <th scope="col" style="box-sizing: border-box;text-align: center;border: 1px solid #000 !important;padding: .75rem;vertical-align: top;border-top: 1px solid #dee2e6;">Item Id</th>
                                <th scope="col" style="box-sizing: border-box;text-align: center;border: 1px solid #000 !important;padding: .75rem;vertical-align: top;border-top: 1px solid #dee2e6;">Comments</th>
                              </tr>
                            </thead>
                            <tbody style="box-sizing: border-box;">
                              <tr style="box-sizing: border-box;">
                                <td style="box-sizing: border-box;text-align: center;border: 1px solid #000 !important;padding: .75rem;vertical-align: top;border-top: 1px solid #dee2e6;"><div class="text-hi" style="box-sizing: border-box;height: 170px;display: flex;align-items: center;justify-content: center;text-align: center;"><p style="box-sizing: border-box;">1</p></div></td>
                                <td style="box-sizing: border-box;text-align: center;border: 1px solid #000 !important;padding: .75rem;vertical-align: top;border-top: 1px solid #dee2e6;"><div class="text-hi" style="box-sizing: border-box;height: 170px;display: flex;align-items: center;justify-content: center;text-align: center;"><p style="box-sizing: border-box;"><?=$breakage->name?> </p></div></td>
                                <td style="box-sizing: border-box;text-align: center;border: 1px solid #000 !important;padding: .75rem;vertical-align: top;border-top: 1px solid #dee2e6;"><div class="text-hi" style="box-sizing: border-box;height: 170px;display: flex;align-items: center;justify-content: center;text-align: center;"><p style="box-sizing: border-box;"><?=$breakage->unique_id?> </p></div></td>
                                <td style="box-sizing: border-box;text-align: center;border: 1px solid #000 !important;padding: .75rem;vertical-align: top;border-top: 1px solid #dee2e6;"><div class="text-hi" style="box-sizing: border-box;height: 170px;display: flex;align-items: center;justify-content: center;text-align: center;"><p style="box-sizing: border-box;"><?=$GatePass->purpose?></p></div></td>
                              </tr>
                            </tbody>
                          </table>
                    </div>
                </div>
                <div class="cash-sig" style="box-sizing: border-box;display: flex;align-items: center;justify-content: space-between;margin-bottom: 10px;">
                    <div class="single-sig text-left" style="box-sizing: border-box;">
                        <p style="box-sizing: border-box;">Created & Issued By</p>
                        <p style="box-sizing: border-box;">Authority</p>
                    </div>
                    <div class="single-sig text-right" style="box-sizing: border-box;">
                        <p style="box-sizing: border-box;">Pass in / out</p>
                        <p style="box-sizing: border-box;">security in-charge</p>
                    </div>
                </div>
                <!--<div class="cash-page" style="box-sizing: border-box;display: flex;align-items: center;justify-content: space-between;">-->
                    <!--<p style="box-sizing: border-box;margin: 0;">page 1/1</p>-->
                    <!--<p style="box-sizing: border-box;margin: 0;">caritas</p>-->
                </div>
            </div>
        <!-- cash-area-end -->
    </body>
</html>