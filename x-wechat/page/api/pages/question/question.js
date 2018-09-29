var config = require("../../../../config.js");
var app=new getApp();

Page({
  
  /**
   * 页面的初始数据
   */
  data: {
    list: []
  },

  kindToggle: function (e) {
    var id = e.currentTarget.id, list = this.data.list;
    for (var i = 0, len = list.length; i < len; ++i) {
      if (list[i].id == id) {
        list[i].open = !list[i].open
      } else {
        list[i].open = false
      }
    }
    this.setData({
      list: list
    });
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    let that=this;
    wx.request({
      url: config["questionUrl"],
      method:"get",
      header: { 'Content-Type': 'application/json' },
      success:function(res)
      {
         let arr=[];
         for(var i in res.data)
         {
           arr.push({ 'id': res.data[i].id, 'name': res.data[i].question, 'open': false, 'content': res.data[i].answer});
         }
        that.setData({ list: arr});  
      }
    })
  },

  //下拉刷新
  onPullDownRefresh: function () {

    let that = this;

    wx.request({
      url: config["questionUrl"],
      method: "get",
      header: { 'Content-Type': 'application/json' },
      success: function (res) {
        let arr = [];
        for (var i in res.data) {
          arr.push({ 'id': res.data[i].id, 'name': res.data[i].question, 'open': false, 'content': res.data[i].answer });
        }
        that.setData({ list: arr });
      }
    })

    wx.stopPullDownRefresh();
  },


})