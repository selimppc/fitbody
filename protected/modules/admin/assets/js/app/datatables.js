/* [ ---- Gebo Admin Panel - datatables ---- ] */

	$(document).ready(function() {
		//* basic
		//gebo_datatbles.dt_a();
		// horizontal scroll
		//gebo_datatbles.dt_b();
		//* large table
		//gebo_datatbles.dt_c();
		//* hideable columns
		//gebo_datatbles.dt_d();
		//* server side proccessing with hiden html
		//gebo_datatbles.dt_e();
		
		if($('#dt_fixed').length) {
			
			function dtInit() {
				// init datatable
				var oTable = $('#dt_fixed').dataTable( {
					"sScrollY": "400px",
					"sScrollX": "100%",
					"sScrollXInner": "150%",
					"bScrollCollapse": true,
					"bPaginate": false,
					"iDisplayLength": 20
				} );
				// init fixed columns
				var oFC = new FixedColumns( oTable, {
					"sLeftWidth": 'relative',
					"iLeftWidth": 20
				} );
				// redraw datatable after window resize
				function resizeDt() {
					oTable.fnDraw();
				};
				var resizeTimer = null;
				$(window).bind('resize', function() {
					if (resizeTimer) clearTimeout(resizeTimer);
					resizeTimer = setTimeout(resizeDt, 100);
				});
			}
			// wait for preloader
			setTimeout(dtInit,1200);
		}
	});
	
	//* calendar
	gebo_datatbles = {
		dt_a: function() {
			$('table[hidden-id="dt_a"]').dataTable({
                "sDom": "<'html'<'span6'<'dt_actions'>l><'span6'f>r>t<'html'<'span6'i><'span6'p>>",
                "sPaginationType": "bootstrap_alt",
				"bPaginate" : false,
                "oLanguage": {
                    "sLengthMenu": "_MENU_ records per page"
                }
            });
		},
        dt_b: function() {
			$('#dt_b').dataTable({
				"sDom": "<'html'<'span6'l><'span6'f>r>t<'html'<'span6'i><'span6'p>>",
                "sScrollX": "100%",
                "sScrollXInner": '110%',
                "sPaginationType": "bootstrap",
                "bScrollCollapse": true 
            });
		},
		dt_c: function() {
                
            var aaData = [];
            for ( var i=1, len=1000 ; i<=len ; i++ ) {
                aaData.push( [ i, i, i, i, i ] );
            };
            
            $('#dt_c').dataTable({
				"sDom": "<'html'<'span6'><'span6'f>r>t<'html'<'span6'i><'span6'>S>",
                "sScrollY": "200px",
                "aaData": aaData,
                "bDeferRender": true
			});
            
            $('#fill_table').click(function(){
                var aaData = [];
                for ( var i=1, len=50000; i <= len; i++){
                    aaData.push( [ i, i, i, i, i ] );
                };
               
                $('#dt_c').dataTable({
                    "sDom": "<'html'<'span6'><'span6'f>r>t<'html'<'span6'i><'span6'>S>",
                    "sScrollY": "200px",
                    "aaData": aaData,
                    "bDestroy": true,
                    "bDeferRender": true
                });
                $(this).remove();
                $('#entries').html('50 000');
                $('.dataTables_scrollHeadInner').css({'height':'34px','top':'0'});
            });
            
		},
		dt_d: function() {
		
			function fnShowHide( iCol ) {
				/* Get the DataTables object again - this is not a recreation, just a get of the object */
				var oTable = $('#dt_d').dataTable();
				 
				var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
				oTable.fnSetColumnVis( iCol, bVis ? false : true );
			};
			
			var oTable = $('#dt_d').dataTable({
				"sDom": "<'html'<'span6'l><'span6'f>r>t<'html'<'span6'i><'span6'p>>",
				"sPaginationType": "bootstrap"
			});
			
			$('#dt_d_nav').on('click','li input',function(){
				fnShowHide( $(this).val() );
			});
		},
		dt_e: function(){
			if($('#dt_e').length) {

				var oTable;
 
				/* Formating function for html details */
				function fnFormatDetails ( nTr )
				{
					var aData = oTable.fnGetData( nTr );
					var sOut = '<table cellpadding="5" cellspacing="0" border="0" class="table table-bordered" >';
					sOut += '<tr><td>Rendering engine:</td><td>'+aData[2]+' '+aData[5]+'</td></tr>';
					sOut += '<tr><td>Link to source:</td><td>Could provide a link here</td></tr>';
					sOut += '<tr><td>Extra info:</td><td>And any further details here (images etc)</td></tr>';
					sOut += '</table>';
					 
					return sOut;
				}
				
				oTable = $('#dt_e').dataTable( {
					"bProcessing": true,
					"bServerSide": true,
                    "sPaginationType": "bootstrap",
                    "sDom": "<'html'<'span6'l><'span6'f>r>t<'html'<'span6'i><'span6'p>>",
					"sAjaxSource": "lib/datatables/server_side.php",
					"aoColumns": [
						{ "sClass": "center", "bSortable": false },
						null,
						null,
						null,
						{ "sClass": "center" },
						{ "sClass": "center" }
					],
					"aaSorting": [[1, 'asc']]
				} );
				
                 
				$('#dt_e').on('click','tbody td img', function () {
					var nTr = $(this).parents('tr')[0];
					if ( oTable.fnIsOpen(nTr) )
					{
						/* This html is already open - close it */
						this.src = "img/details_open.png";
						oTable.fnClose( nTr );
					} else {
						/* Open this html */
						this.src = "img/details_close.png";
						oTable.fnOpen( nTr, fnFormatDetails(nTr), 'details' );
					}
				} );

			}
		}
	};