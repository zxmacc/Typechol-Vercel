<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-12-06 14:30
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */
class Setting_Plugin
{
    function render()
    {
        ?>
        <script src="<?php echo Freewind_Helper::freeCdn('plugin/layui/layui.min.js') ?>"></script>
        <script>
            $.fn.serializeObject = function () {
                var o = {};
                var a = this.serializeArray();
                $.each(a, function () {
                    if (o[this.name] !== undefined) {
                        if (!o[this.name].push) {
                            o[this.name] = [o[this.name]];
                        }
                        o[this.name].push(this.value || '');
                    } else {
                        o[this.name] = this.value || '';
                    }
                });
                return o;
            };
            $("div[role=form]").removeClass('col-tb-8 col-tb-offset-2').addClass('col-tb-12').css('background-color', '#fff')
            let oldform = $("form:not(form[class=layui-form])")
            let newform = $("form[class=layui-form]")
            newform.attr('action', oldform.attr('action'))
            newform.attr('method', oldform.attr('method'))
            newform.attr('enctype', oldform.attr('enctype'))
            let themeData = oldform.serializeObject()
            for (key in themeData) {
                if (themeData[key])
                    $(`.layui-form [name=${key}]`).val(themeData[key])
            }
            pageData = themeData['freeNavList'] ? JSON.parse(themeData['freeNavList']) : [{}]
            recomData = themeData['freeRecomList'] ? JSON.parse(themeData['freeRecomList']) : [{}]
            linkData = themeData['freeLinkList'] ? JSON.parse(themeData['freeLinkList']) : [{}]
            menuData = themeData['freeMenuList'] ? JSON.parse(themeData['freeMenuList']) : [{}]
            oldform.remove()
        </script>
        <script type="text/html" id="tool-bar">
            <a class="layui-btn layui-btn-xs  layui-btn-normal" lay-event="add"><i
                        class="layui-icon layui-icon-addition"></i></a>
            <a class="layui-btn layui-btn-xs  layui-btn-danger" lay-event="del"><i
                        class="layui-icon layui-icon-subtraction"></i></a>
        </script>
        <script>
            layui.use(['form', 'table', 'colorpicker', 'slider'], function () {
                const form = layui.form;
                const table = layui.table;
                const colorpicker = layui.colorpicker;
                const slider = layui.slider;
                colorpicker.render({
                    elem: '#bg-color-picker',  //绑定元素
                    color: $("input[name=freeBgColor]").val(),
                    predefine: true,
                    colors: [$(".layui-input-inline input[name=freeBgColor]").val(), '#EEEEEF', '#BCD2DC', '#D4D2D4', '#4392B4', '#FDECD2'],
                    done: function (color) {
                        $("input[name=freeBgColor]").val(color)
                    }
                });

                slider.render({
                    elem: '#free-bg-opcity',  //绑定元素
                    value: $('input[name=freeBgOpcity]').val(),
                    change: function (value) {
                        $('input[name=freeBgOpcity]').val(value);
                    }
                })
                ;

                let newform = $(".layui-form")

                $('#mail-check').on('click', function () {
                    let loadIndex = layer.load(1, {
                        shade: [0.1, '#fff'] //0.1透明度的白色背景
                    });
                    $.ajax({
                        url: '/mail/check',
                        dataType: 'json',
                        success: res => {
                            layer.close(loadIndex)
                            layer.msg(res.msg, {icon: res.success ? 1 : 2})
                        },
                        error: res => {
                            layer.close(loadIndex)
                        }
                    })
                })
                //监听提交

                form.on('submit(formDemo)', function (data) {
                    data.field['freeNavList'] = JSON.stringify(pageData)
                    data.field['freeRecomList'] = JSON.stringify(recomData)
                    data.field['freeLinkList'] = JSON.stringify(linkData)
                    data.field['freeMenuList'] = JSON.stringify(menuData)
                    let index = layer.load()
                    $.ajax({
                        url: newform.attr('action'),
                        type: newform.attr('method'),
                        data: data.field,
                        success: res => {
                            layer.close(index)
                            layer.msg("更新成功", {icon: 1})
                            location.reload()
                        },
                        error: res => {
                            layer.close(index)
                        }
                    })
                    return false
                });
                table.on('tool(page-table)', function (obj) {
                    let layEvent = obj.event;
                    if (layEvent === 'del') { //删除
                        layer.confirm('真的删除行么', function (index) {
                            if (pageData && pageData.length > 1) {
                                let delidx = -1;
                                for (let i = 0; i < pageData.length; i++) {
                                    if (pageData[i]['name'] === obj['data']['name']) {
                                        delidx = i;
                                        break;
                                    }
                                }
                                pageData.splice(delidx, 1);
                            } else {
                                pageData[0] = {}
                            }
                            table.reload("page-table", {data: pageData})
                            layer.msg('删除成功', {icon: 1})
                            layer.close(index);
                        });
                    } else if (layEvent === 'add') {
                        pageData.unshift({})
                        table.reload("page-table", {data: pageData})
                    }
                })
                table.render({
                    elem: '#page-table',
                    height: 600,
                    data: pageData,
                    page: true,
                    cols: [[
                        {field: 'name', title: '页面名称', width: 200, edit: true},
                        {field: 'url', title: '页面URL', edit: true},
                        {field: 'target', title: '新页面打开', width: 100, edit: true},
                        {field: '', title: '操作', width: 150, toolbar: "#tool-bar"}
                    ]]
                });

                table.render({
                    elem: '#recom-table',
                    height: 600,
                    data: recomData,
                    page: true,
                    cols: [[
                        {field: 'title', title: '标题', width: 200, edit: true},
                        {field: 'desc', title: '描述', edit: true},
                        {field: 'pic', title: '图片URL', edit: true},
                        {field: 'url', title: '跳转URL', edit: true},
                        {field: '', title: '操作', width: 150, toolbar: "#tool-bar"}
                    ]]
                });

                table.render({
                    elem: '#link-table',
                    height: 600,
                    page: true,
                    data: linkData,
                    cols: [[
                        {field: 'name', title: '名称', width: 200, edit: true},
                        {field: 'link', title: '链接', edit: true},
                        {field: 'icon', title: '图标(可选)', edit: true},
                        {field: 'desc', title: '描述(可选)', edit: true},
                        {field: '', title: '操作', width: 150, toolbar: "#tool-bar"}
                    ]]
                });

                table.render({
                    elem: '#menu-table',
                    height: 600,
                    data: menuData,
                    page: true,
                    cols: [[
                        {field: 'icon', title: '图标', width: 200, edit: true},
                        {field: 'name', title: '名称', edit: true},
                        {field: 'url', title: '跳转地址', edit: true},
                        {field: '', title: '操作', width: 150, toolbar: "#tool-bar"}
                    ]]
                });

                table.on('tool(recom-table)', function (obj) {
                    let layEvent = obj.event;
                    if (layEvent === 'del') { //删除
                        layer.confirm('真的删除行么', function (index) {
                            if (recomData && recomData.length > 1) {
                                let delidx = -1;
                                for (let i = 0; i < recomData.length; i++) {
                                    if (recomData[i]['title'] === obj['data']['title']) {
                                        delidx = i;
                                        break;
                                    }
                                }
                                recomData.splice(delidx, 1);
                            } else {
                                recomData[0] = {}
                            }
                            table.reload("recom-table", {data: recomData})
                            layer.msg('删除成功', {icon: 1})
                            layer.close(index);
                        });
                    } else if (layEvent === 'add') {
                        recomData.unshift({})
                        table.reload("recom-table", {data: recomData})
                    }
                })

                table.on('tool(link-table)', function (obj) {
                    let layEvent = obj.event;
                    if (layEvent === 'del') { //删除
                        layer.confirm('真的删除行么', function (index) {
                            if (linkData && linkData.length > 1) {
                                let delidx = -1;
                                for (let i = 0; i < linkData.length; i++) {
                                    if (linkData[i]['link'] === obj['data']['link']) {
                                        delidx = i;
                                        break;
                                    }
                                }
                                linkData.splice(delidx, 1);
                            } else {
                                linkData[0] = {}
                            }
                            table.reload("link-table", {data: linkData})
                            layer.msg('删除成功', {icon: 1})
                            layer.close(index);
                        });
                    } else if (layEvent === 'add') {
                        linkData.unshift({})
                        table.reload("link-table", {data: linkData})
                    }
                })

                table.on('tool(menu-table)', function (obj) {
                    let layEvent = obj.event;
                    if (layEvent === 'del') { //删除
                        layer.confirm('真的删除行么', function (index) {
                            if (menuData && menuData.length > 1) {
                                let delidx = -1;
                                for (let i = 0; i < menuData.length; i++) {
                                    if (menuData[i]['url'] === obj['data']['url']) {
                                        delidx = i;
                                        break;
                                    }
                                }
                                menuData.splice(delidx, 1);
                            } else {
                                menuData[0] = {}
                            }
                            table.reload("menu-table", {data: menuData})
                            layer.msg('删除成功', {icon: 1})
                            layer.close(index);
                        });
                    } else if (layEvent === 'add') {
                        menuData.unshift({})
                        table.reload("menu-table", {data: menuData})
                    }
                })
                $("#check-update").on('click', function () {
                    let index = layer.load(1, {
                        shade: [0.1, '#fff'] //0.1透明度的白色背景
                    });
                    $.ajax({
                        url: `/update/check`,
                        method: 'post',
                        success: res => {
                            layer.close(index)
                            let result = JSON.parse(res)
                            if (result.data.islast) {
                                layer.msg(result.msg, {icon: result.success ? 1 : 2})
                            } else {
                                let ci = layer.confirm(result.msg + "，是否下载最新版本", {btn: ['更新', "取消"]}, function () {
                                    layer.close(ci)
                                    window.open(result.data.url)
                                })
                            }
                        }
                    })
                })
                // table.reload("page-table",{})
            });
        </script>
        <?php
    }
}