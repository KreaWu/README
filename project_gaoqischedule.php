<?
//enable session

header("Content-Type: text/html; charset=utf-8");
libxml_disable_entity_loader(false);
require_once("../Include/PHPfuctions.php");

identitycheck("项目部", "总裁办");
//connet to zhengshen database
require("../Include/connectdb.php");
require("../Include/ftp.php");
require("../Include/phpparameters.php");

//判断文件是否存在，不论远程或本地文件
function my_file_exists($file)  
{  
    if(preg_match('/^http:\/\//',$file)){  
        //远程文件  
        if(ini_get('allow_url_fopen')){  
            if(@fopen($file,'r')) return true;  
        }  
        else{  
            $parseurl=parse_url($file);  
            $host=$parseurl['host'];  
            $path=$parseurl['path'];  
            $fp=fsockopen($host,80, $errno, $errstr, 10);  
            if(!$fp)return false;  
            fputs($fp,"GET {$path} HTTP/1.1 \r\nhost:{$host}\r\n\r\n");  
            if(preg_match('/HTTP\/1.1 200/',fgets($fp,1024))) return true;  
        }  
        return false;  
    }  
    return file_exists($file);  
} 

$projectid = $_GET['projectid'];
$serviceid=$_GET['serviceid'];
$clientid= $_GET['clientid'];
$clientname=$_GET['clientname'];

 $filepath = 'http://'.$ftpip.'/Upload/'.$clientid.'/contracts/'.$projectid.'/gaoqischedule.xml';

if(my_file_exists($filepath)){
	$contents = file_get_contents($filepath);
	$xmlcontent = simplexml_load_string($contents);
//print_r($xmlcontent);
}

?>

<? require("../Include/headerprint_project.php")?>
 <!-- SWEET ALERT CSS-->
<link href="../css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
<div class="row">
    <div class="col-lg-12">
          
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>高企时间进度表</h2>

            </div>
            <div class="col-lg-2">

            </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row"></div>
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>高企时间进度表</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div id="gaoqischedule">
							<table border="0" width="100%">
								<tbody>
									<tr>
										<td colspan="2">
											<? $phppath = 'http://'.$ftpip.'/functions/savegaoqischedulexml.php';?>
											<form enctype="multipart/form-data" action="<? echo($phppath)?>?projectid=<? echo($projectid)?>&serviceid=<? echo($serviceid)?>&clientid=<? echo($clientid)?>&clientname=<? echo($clientname)?>" id="insertexcel" name="insertexcel" method="post">
												<table>
											  		<tr>
														<td>Excel导入</td>
														<td><input type="file" name="gaoqiexcel" id="gaoqiexcel"></td>
														<td><input type="submit" id="insertexcel" name="insertexcel" value="导入excel" /></td>
													</tr>
												</table>
											</form>
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td colspan="2">
											<table class="table table-bordered table-striped" width="100%">
											  <tbody>
												  <? if(my_file_exists($filepath)):?>
												  <? $rownum = count($xmlcontent->rows); //$columnnum = count($xmlcontent->rows['id']); //$j=0;?>
												  <?// echo $columnnum;?>
												  <? foreach($xmlcontent->rows as $row){
												  		if($row['id'] == 1){
												  ?>
												  		<tr>
															<td colspan="7"><textarea id="<? echo($row['id'])?>&A" style="text-align: center;width: 100%"><? echo($row->A)?></textarea></td>
												  		</tr>
													  
													<? } elseif($row['id'] == 2){?>
												  		<tr>
														  <?	for($a='A';$a<='G';$a++){?>
																	  <td>
																			<input type="text" id="<? echo($row['id'] ."&".$a)?>" <? if($a == 'A'):?>col="5"<? endif?> value="<? echo($row->$a);?>" border="0">
																	  </td>
														  <? 	}?>
															
													 	 </tr>
												  
													<? }else{?>
															<tr>
														   
														  		<?	for($a='A';$a<='G';$a++){
																		if($a == 'A' || $a == 'B'){?>
																			  <td>
																					<textarea id="<? echo($row['id'])?>&<? echo($a)?>" <? if($a == 'A'):?>width="40px"<? endif?>><? echo($row->$a);?></textarea>
																			  </td>
																<?      }elseif($a == 'E'){?>
																			  <td>
																					<input type="date" id="<? echo($row['id'])?>&<? echo($a)?>" value="<? echo($row->$a);?>" >
																			  </td>
																
																<?		}elseif($a == 'F'){?>
																				<td>
																					<select id="<? echo($row['id'])?>&<? echo($a)?>">
																						<option value="进行中" <? if($row->$a == '进行中'):?> selected <? endif?>>进行中</option>
																						<option value="完成" <? if($row->$a == '完成'):?> selected <? endif?>>完成</option>
																						<option value="延迟" <? if($row->$a == '延迟'):?> selected <? endif?>>延迟</option>
																					</select>
																					
																					<?// echo($row->$a);?>
																			  </td>
																<?		}else{?>
																			  <td>
																				  <textarea id="<? echo($row['id'])?>&<? echo($a)?>"><? echo($row->$a);?></textarea>
																			  </td>
														  		<? 	}
																	}?>
															
													 	 </tr>
													<?	}
														}?>
												  
											<? else:  $j= 1;?>
												<tr>
													<td colspan="7"><textarea id="1&A" style="text-align: center;width: 100%">高新技术企业项目材料清单及进度计划</textarea></td>
												</tr>  
												  <tr>
													  <td><input id="2&A" type="text" value="序号"></td>
													  <td><input id="2&B" type="text" value="名称"></td>
													  <td><input id="2&C" type="text" value="内容"></td>
													  <td><input id="2&D" type="text" value="责任方"></td>
													  <td><input id="2&E" type="text" value="截止日期"></td>
													  <td><input id="2&F" type="text" value="进度状态"></td>
													  <td><input id="2&G" type="text" value="备注"></td>
												  </tr>
												  <? while($j<38):?>
													  <tr>
														  <? for($colid='A';$colid<='G';$colid++):?>
														  	<? if($colid == 'E'):?>
														  		<td><input type="date" id="<? echo($j)?>&<? echo($colid)?>" ></td>
														 	<? elseif($colid == 'F'):?>
														  		<td>
																	<select id="<? echo($j)?>&<? echo($colid)?>">
																		<option value="进行中">进行中</option>
																		<option value="完成">完成</option>
																		<option value="延迟">延迟</option>
																	</select>
														        </td>
														  <? else:?>
														  		<td>
																  <textarea id="<? echo($j)?>&<? echo($colid)?>"></textarea>
															    </td>
														  <? endif?>
														  <? endfor?>
													  </tr>
												  <? $j++; endwhile?>
											<? endif ?>
											  </tbody>
											</table>
										</td>
									</tr>
									
									<tr>
										<td>
											<!--<input type="button" id="outputexcel" name="outputexcel" value="导出excel" onClick="outputexcel(<? echo($rownum)?>)">-->
											<a href="../html/project_outputgaoqiexcel.php?projectid=<? echo($projectid)?>&serviceid=<? echo($serviceid)?>&clientid=<? echo($clientid)?>&clientname=<? echo($clientname)?>" target="_blank" title="导出excel">导出excel</a>
										</td>
										<? $postinfo = $projectid.'&&'.$serviceid.'&&'.$clientid.'&&'.$clientname;
										//echo($postinfo);
										?>
										<td>
											<input type="button" id="savexml" name="savexml" value="保存" onClick="savexml('<? echo($rownum)?>', '<? echo($postinfo)?>', '<? echo($ftpip)?>')">
										</td>
									</tr>
								</tbody>
							</table>
                           
                        </div>
					</div>
                </div>				
            </div>
        </div>
     </div>
 </div>
 </div>
<div>
	<? $postpath =  'http://'.$ftpip.'/functions/postgaoqischedulexml.php';?>
	<form id="hiddenpostform" name="hiddenpostform" method="post" action="<? echo($postpath)?>"></form>
</div>
<? require("../Include/footerprint_project.php")?>

<!-- Mainly scripts -->
<script src="../js/jquery-2.1.1.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="../js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="../js/inspinia.js"></script>
<script src="../js/plugins/pace/pace.min.js"></script>
<!-- Sweet Alert-->
<script src="../js/plugins/sweetalert/sweetalert.min.js"></script>

<!--  刷新父页面，在提交本页面的时候  -->
<? if(isset($_POST["submitevaluationform"])):?>
<script language="javascript">
	window.opener.location.reload();
</script>
<? endif?>

<!--  使用简化版面 -->
<script>
	$(document).ready(function() {
		document.getElementById("navbarcontrol").click();
		document.getElementById('topmenucontrol').style.display = "none";
		document.getElementById('sidemenucontrol').style.display = "none";
		
		//页面title，需每页更改
		document.title = document.title + " | 高企进度表";
	});
</script>

<script>
	
//不支持ajax跨域提交（同源策略）
/*	function savexml(totalrows, postinfo, ftpip){
			//alert(ftpip);
		var r = 2,c,id,xmlinfocol,xmlinforow,xmlinfo,letter;
		xmlinfocol = document.getElementById("1&A").value;
		xmlinfo = xmlinfocol + '&&&&&&&&&&&&';
		
	
		for(r;r<=totalrows;r++){
			for(c=0;c<7;c++){
				letter = String.fromCharCode(65+c)
				id = r+'&'+letter;
				xmlinfocol = document.getElementById(id).value;
				if(c == 0)
					xmlinforow = xmlinfocol;
				else
					xmlinforow=xmlinforow+'&&'+xmlinfocol;
			}
			
		xmlinfo = xmlinfo+'$$'+xmlinforow;
		}
		//alert(xmlinfo);
		
		$.ajax({  
			type: 'get',
			url: 'http://'+ftpip+'/functions/postgaoqischedulexml.php',
			data:{
				xmlcontent:xmlinfo,
				getinfo:postinfo
			},
			cache: false,
			dataType: 'jsonp',
			success: savesuccess(),
			error:function(){
			alert("修改失败:(");
			}
		});
	}
	function savesuccess(){
		swal({
			  title: "保存成功！",
			  text: "1秒后自动关闭",
			  animation: "slide-from-top",
			  timer: 1000,
			  showConfirmButton: false,
			  background: "green"
			});
	}*/
	
	function savexml(totalrows, postinfo, ftpip){
		var postform = document.getElementById("hiddenpostform");
		postform.action ='http://'+ftpip+'/functions/postgaoqischedulexml.php';
		
		var r = 2,c,id,xmlinfocol,xmlinforow,xmlinfo,letter;
		xmlinfocol = document.getElementById("1&A").value;
		xmlinfo = xmlinfocol + '&&&&&&&&&&&&';
		
	
		for(r;r<=totalrows;r++){
			for(c=0;c<7;c++){
				letter = String.fromCharCode(65+c)
				id = r+'&'+letter;
				xmlinfocol = document.getElementById(id).value;
				if(c == 0)
					xmlinforow = xmlinfocol;
				else
					xmlinforow=xmlinforow+'&&'+xmlinfocol;
			}
			xmlinfo = xmlinfo+'$$'+xmlinforow;
		}
		//alert(xmlinfo);
		var input1 = document.createElement("input");
		input1.type = "text";
		input1.name = "xmlcontent";
		input1.id = "xmlcontent";
		input1.value = xmlinfo;
		postform.appendChild(input1);
		
		var input2 = document.createElement("input");
		input2.type = "text";
		input2.name = "getinfo";
		input2.id = "getinfo";
		input2.value = postinfo;
		postform.appendChild(input2);

		postform.submit();
		
		//window.opener.location.reload();
	}
</script>
