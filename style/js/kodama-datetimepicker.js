$(function () {
  $('.kodama-datepicker').datetimepicker({
    yearSuffix: '年',
    dayViewHeaderFormat: 'YYYY{0} MMMM',
    tooltips: {
      today: '今日',
      clear: '選択をクリア',
      close: '閉じる',
      selectMonth: '月を選択',
      prevMonth: '前月',
      nextMonth: '次月',
      selectYear: '年を選択',
      prevYear: '前年',
      nextYear: '次年',
      selectTime: '時間を選択',
      selectDate: '日付を選択',
      prevDecade: '前期間',
      nextDecade: '次期間',
      selectDecade: '期間を選択',
      prevCentury: '前世紀',
      nextCentury: '次世紀'
    },
    format: 'YYYY-MM-DD',
    locale: 'ja',
    firstDay: 0,
    //viewMode: 'times',
    buttons: {
        showToday: true,
        showClear: true,
        showClose: true,
    },
    //debug:true
  });

  $('.kodama-monthpicker').datetimepicker({
    yearSuffix: '年',
    dayViewHeaderFormat: 'YYYY{0} MMMM',
    tooltips: {
      today: '今日',
      clear: '選択をクリア',
      close: '閉じる',
      selectMonth: '月を選択',
      prevMonth: '前月',
      nextMonth: '次月',
      selectYear: '年を選択',
      prevYear: '前年',
      nextYear: '次年',
      selectTime: '時間を選択',
      selectDate: '日付を選択',
      prevDecade: '前期間',
      nextDecade: '次期間',
      selectDecade: '期間を選択',
      prevCentury: '前世紀',
      nextCentury: '次世紀'
    },
    format: 'YYYY-MM',
    locale: 'ja',
    firstDay: 0,
    //viewMode: 'months',
    buttons: {
        showToday: true,
        showClear: true,
        showClose: true,
    },
    //debug:true
  });
  
  $('.kodama-timepicker').datetimepicker({
    yearSuffix: '年',
    dayViewHeaderFormat: 'YYYY{0} MMMM',
    tooltips: {
      today: '今日',
      clear: '選択をクリア',
      close: '閉じる',
      selectMonth: '月を選択',
      prevMonth: '前月',
      nextMonth: '次月',
      selectYear: '年を選択',
      prevYear: '前年',
      nextYear: '次年',
      selectTime: '時間を選択',
      selectDate: '日付を選択',
      prevDecade: '前期間',
      nextDecade: '次期間',
      selectDecade: '期間を選択',
      prevCentury: '前世紀',
      nextCentury: '次世紀'
    },
    format: 'HH:mm:SS',
    locale: 'ja',
    firstDay: 0,
    viewMode: 'times',
    buttons: {
        showClear: true,
        showClose: true,
    },
    //debug:true
  });
});