window.onload = function() {
      myResultsTable.init('window');      
}
$(document).ready(function() {      
      myResultsTable.init('document'); 
});

class eCMSResultsTable {

      constructor() {
            this.nbImgPending = 0;
            this.initParts = new Array();
      }

      init(what) {
            this.initParts.push(what);

            if (what==='document') {
                  this.initFoldersOpening();
            } else if (what==='window') {
                  this.uniformColsWidth();
            }
      }

      uniformColsWidth() {

            if ($('.lang_block').find('table').length>1) {
      
                  let tCols = new Array();
                  $('.lang_block:eq(0) table:eq(0) tr:first-child td').each(function(i) {
                        let tColWidth = $(this).width();
                        let tMaxWidth = parseInt($(this).css('max-width'))/100 * $('.lang_block:eq(0) table:eq(0)').width();
                        if (tColWidth>tMaxWidth) {
                              tColWidth = tMaxWidth;
                        }
                        tCols[i] = tColWidth;
                  });
      
                  $('.lang_block').find('table').each(function(z) {
                        $(this).find('tr:first-child td').each(function(i) {
                              $(this).css({'width':tCols[i]+'px'});
                        });
                  });
      
            }
      
      }
      
      initFoldersOpening() {
            $('h2.folder').click(function() {
                  const tFolderTable = $(this).next('.table_container');
                  if (tFolderTable.hasClass('closed')) {
                        myResultsTable.openFolder(tFolderTable);					
                  } else {
                        myResultsTable.closeFolder(tFolderTable);
                  }						
            });
      
            $('h2.folder').each(function() {                  
                  myResultsTable.closeFolder($(this).next('.table_container'));
            });
      }
      
      openFolder(tFolderTable) {
            $(tFolderTable).removeClass('closed');
            $(tFolderTable).prev('h2.folder').removeClass('closed');
            
            this.loadFolderImages(tFolderTable);
      }
      
      closeFolder(tFolderTable) {
            $(tFolderTable).addClass('closed');
            $(tFolderTable).prev('h2.folder').addClass('closed');
      }
      
      
      loadFolderImages(tFolderTable) {			
            if (tFolderTable.hasClass('loaded')===false) {
      
                  let tEstimate = tFolderTable.find('tr:gt(0)').length * 200;
                  tFolderTable.css({'max-height':tEstimate+'px'});
      
                  tFolderTable.addClass('loaded');
                 
                  this.nbImgPending = tFolderTable.find('a.img_full').length;
                  tFolderTable.find('a.img_full').each(function() {
                        let tFilePath = $(this).attr('href').replace('_full', '');
                        let tTitle = $(this).attr('title');
      
                        let tImg = document.createElement('img');
                        tImg.title = tTitle;
                        tImg.onload = function() {
                              myResultsTable.nbImgPending--;
                              if (myResultsTable.nbImgPending==0) {
                                    let tTransition = tFolderTable.css('transition');
                                    tFolderTable.css({'transition':'none', 'max-height':''});
                                    let tHeight = tFolderTable.outerHeight();
                                    tFolderTable.css({'max-height':tEstimate+'px'});
                                    tFolderTable.css('transition', tTransition);
                                    tFolderTable.css({'max-height':tHeight+'px'});
                              }
                        }
                        tImg.src = tFilePath;
                        $(this).html('')[0].appendChild(tImg);
                  });		
                                                           
            }
      }

}

let myResultsTable = new eCMSResultsTable();