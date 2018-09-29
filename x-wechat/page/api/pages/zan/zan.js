
var config = require("../../../../config.js");
var app = new getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    task_id:0,
    user_id:0,
    nick_name:'',
    avatar_url:'',
    count:0,
    click_number:0,
  },

  //常见问题
  questionBtn:function(){
    wx.navigateTo({
      url: '../question/question',
    })
  },

  //为他/她点赞
  clickBtn:function(e){

    let task_id = e.currentTarget.dataset.taskid;
    let user_id = e.currentTarget.dataset.userid;

    wx.login({
      success:res=>{
        if (res.code) {
          wx.request({
            url: config['openIdUrl'] + "?code=" + res.code,
            header: { 'Content-Type': 'application/json' },
            success: function (res) {

             let open_id = res.data;
              wx.request({
                url: config['clickShareTaskUrl'],
                header: { 'Content-Type': 'application/json' },
                data: { 'open_id': open_id, "task_id": task_id, 'user_id': user_id},
                method:"GET",
                success:res=>{

                  let content ="点赞成功";
                  if (res.data.status!=0)
                  {
                    content=res.data.msg;
                  }
                  
                  wx.showToast({ title: content });
                  setTimeout(function(){
                    wx.reLaunch({
                      url: '/page/component/index',
                    })
                  },3000);
                }
              })

            }
          })
        }
      }
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

    let task_id = options.task_id
    let open_id = options.open_id

    wx.request({
      url: config['getShareTaskUrl'] + "?task_id=" + task_id + "&open_id=" + open_id,
      header: { 'Content-Type': 'application/json' },
      success:res=>{

        if (res.data.status==0)
        {
          this.setData({ 
            nick_name: res.data.row.customer.nick_name, 
            avatar_url: res.data.row.customer.avatar_url,
            count: res.data.row.task.count,
            click_number: res.data.row.data.click_number,
            task_id:res.data.row.task_id,
            user_id: res.data.row.customer.id,
          });
        }
      }
    })

  },
})