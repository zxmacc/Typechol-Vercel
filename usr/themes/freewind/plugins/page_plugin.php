<?php
/**
 * Author: Mr丶冷文
 * Date: 2021-12-21 11:07
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */
?>
<script>
    if (!window['pagewrite']) {
        window['pagewrite'] = true;

        function loadJs(url, callback) {
            let script = document.createElement('script');
            script.type = "text/javascript";
            if (typeof (callback) != "undefined") {
                if (script.readyState) {
                    script.onreadystatechange = function () {
                        if (script.readyState === "loaded" || script.readyState === "complete") {
                            script.onreadystatechange = null;
                            callback();
                        }
                    }
                } else {
                    script.onload = function () {
                        callback();
                    }
                }
            }
            script.src = url;
            document.body.appendChild(script);
        }

        loadJs("<?php echo Freewind_Helper::freeCdn('plugin/fw-editor/fw.editor.min.js') ?>", function () {
            loadJs("<?php echo Freewind_Helper::freeCdn('plugin/layui/layui.min.js') ?>", function () {
                layui.use('colorpicker', function () {
                    let editor = new Freewind.Editor("text", {
                        height: 500,
                        menu: [
                            "undo", "redo",
                            "bold", "italic", 'strikethrough', "separator",
                            "linecode", "quote", "headline", "orderlist", "unorderlist", "link",
                            "image", "table", "code", "datetime",
                            "fwfile", "fwface", "fwalert", "fwmsg", "fwtab", 'fwgroup', 'fwbtn', 'fwbili', 'fwmusic', 'colorline',
                            "fullscreen", "views",
                            "help",
                        ],
                        menuExt: {
                            fwfile: {
                                icon: '<svg t="1639848935679" class="icon" viewBox="0 0 1570 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="19969" width="48" height="48"><path d="M1283.072 430.933333c0-4.266667 0.7168-8.533333 0.7168-12.8C1283.7888 187.050667 1102.2336 0 878.2848 0c-161.553067 0-300.373333 97.416533-365.568 238.250667a205.243733 205.243733 0 0 0-93.866667-23.1424c-103.424 0-189.6448 77.858133-206.097066 179.541333C88.6784 438.0672 0 558.626133 0 700.450133 0 878.933333 140.526933 1024 313.685333 1024h359.2192v-284.433067h-168.925866L785.066667 441.924267l281.088 297.301333h-168.96v284.433067h386.594133c158.4128 0 286.344533-133.358933 286.344533-296.5504 0-163.191467-128.6144-295.8336-287.061333-296.174934z" p-id="19970" fill="#ABB2BF"></path></svg>',
                                call: function () {
                                    $("#freewind-mask").fadeIn()
                                }
                            },
                            fwface: {
                                icon: '<svg t="1639849043840" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="20853" width="48" height="48"><path d="M512.005063 1023.999068A512.1261 512.1261 0 0 1 312.699221 40.247954 512.1261 512.1261 0 0 1 711.300779 983.751115a508.724009 508.724009 0 0 1-199.295716 40.247953z m0-962.670299A450.797332 450.797332 0 0 0 336.594862 927.272351 450.799357 450.799357 0 0 0 687.415264 96.726717a447.820502 447.820502 0 0 0-175.410201-35.397948z" fill="#ABB2BF" p-id="20854"></path><path d="M331.400597 329.709211m-58.524068 0a58.524068 58.524068 0 1 0 117.048137 0 58.524068 58.524068 0 1 0-117.048137 0Z" fill="#ABB2BF" p-id="20855"></path><path d="M691.921009 329.709211m-58.524068 0a58.524068 58.524068 0 1 0 117.048137 0 58.524068 58.524068 0 1 0-117.048137 0Z" fill="#ABB2BF" p-id="20856"></path><path d="M512.065814 880.979611a369.086392 369.086392 0 0 1-369.359774-369.359774 32.400868 32.400868 0 0 1 64.801737 0 304.558037 304.558037 0 0 0 609.116075 0 32.400868 32.400868 0 0 1 64.801736 0 369.066141 369.066141 0 0 1-369.359774 369.359774z" fill="#ABB2BF" p-id="20857"></path></svg>',
                                call: function () {
                                    let faceList = []
                                    $.ajax({
                                        url: "<?php echo Freewind_Helper::freeCdn('json/emotions.json') ?>",
                                        async: false,
                                        dataType: "json",
                                        success: res => {
                                            for (let i = 0; i < res.length; i++) {
                                                faceList.push({
                                                    src: '<?php echo Freewind_Helper::freeCdn('') ?>' + res[i]['src'],
                                                    alt: res[i]['alt']
                                                })
                                            }
                                        }
                                    })
                                    $.ajax({
                                        url: "<?php echo Freewind_Helper::freeCdn('json/expression.json') ?>    ",
                                        async: false,
                                        dataType: "json",
                                        success: res => {
                                            for (let i = 0; i < res.length; i++) {
                                                faceList.push({
                                                    src: '<?php echo Freewind_Helper::freeCdn('') ?>' + res[i]['src'],
                                                    alt: res[i]['alt']
                                                })
                                            }
                                        }
                                    })
                                    let content = '<div class="face-list">'
                                    for (let i = 0; i < faceList.length; i++) {
                                        content += `<a href="javascript:void(0)" data-value="![${faceList[i]['alt'].replaceAll("[", "").replaceAll("]", "")}](${faceList[i]['src']})"><img src="${faceList[i]['src']}" alt="${faceList[i]['alt']}"></a>`
                                    }
                                    content += '</div>'
                                    layer.open({
                                        type: 1,
                                        width: 600,
                                        title: '插入表情',
                                        content: content,
                                    })
                                    $(".face-list a").on('click', function () {
                                        editor.cm.replaceSelection($(this).data('value'));
                                    })
                                }
                            },
                            fwmsg: {
                                icon: '<svg t="1639849580933" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="23197" width="48" height="48"><path d="M642.160874 894.856008l0 11.1857q0 5.084409 0.508441 11.1857t0.508441 12.202582q-1.016882 19.320755-10.677259 42.200596t-37.116187 33.048659q-11.1857 4.067527-28.981132 11.694141t-53.386296 7.626614q-30.506455 0-50.33565-7.118173t-31.014896-9.151936q-14.236346-3.050645-22.879841-12.711023t-13.219464-21.862959-5.59285-24.913605-1.016882-22.879841l0-31.523337zM862.82423 229.815293q26.438928 62.029791 30.506455 116.94141t-6.101291 101.179742-29.489573 82.875869-40.166832 64.063555-39.14995 45.251241-25.422046 23.896723q-10.168818 9.151936-18.812314 13.727905t-15.253227 7.626614-12.711023 7.118173-12.202582 13.219464q-13.219464 19.320755-20.337637 37.624628t-12.202582 35.590864q-5.084409 14.236346-11.1857 23.388282t-13.219464 16.270109q-8.135055 8.135055-16.270109 13.219464l-223.714002 0q-8.135055-5.084409-15.253227-13.219464-7.118173-7.118173-14.236346-18.303873t-12.202582-28.472691q-10.168818-29.489573-24.913605-47.285005t-38.133069-35.082423q-16.270109-12.202582-37.116187-32.540218t-41.183714-47.793446-38.641509-61.521351-29.489573-74.740814-13.219464-86.943396 9.151936-97.112214q17.286991-81.350546 59.996028-136.770606t95.586892-88.97716 109.314796-48.301887 102.196624-14.744786q47.793446 0 99.654419 13.219464t100.16286 41.183714 88.468719 71.181728 65.588878 104.230387zM760.119166 437.259186q43.725919-177.95432-113.890765-271.507448-26.438928-16.270109-61.521351-25.422046t-71.690169-9.151936-72.707051 9.151936-64.571996 28.472691q-69.147964 47.793446-93.553128 103.721946t-22.3714 117.958292q1.016882 34.573982 11.694141 62.029791t25.422046 49.82721 30.506455 39.14995 27.96425 29.998014q25.422046 26.438928 44.7428 46.268123t30.506455 50.33565q11.1857 29.489573 35.082423 36.099305t44.234359 6.609732q26.438928 0 50.844091-11.694141t34.573982-36.099305q5.084409-14.236346 20.846077-34.573982t47.285005-56.945382q16.270109-19.320755 31.014896-33.5571t27.455809-28.472691 22.3714-31.014896 15.761668-41.183714z" p-id="23198" fill="#ABB2BF"></path></svg>',
                                call: function () {
                                    let content = `<div class="fw-layer-content" style="width: 200px;">
                <div class="fw-form-item">
                <label>消息类型：</label>
                <select id="fw-alert" style="width: 120px;">
                <option value="success">成功</option>
                <option value="error">失败</option>
                <option value="info">信息</option>
                <option value="warning">警告</option>
                </select>
                </div>
                </div>
                `
                                    let idx = layer.open({
                                        type: 1,
                                        width: 600,
                                        title: '插入消息',
                                        btn: ['确定', '取消'],
                                        content: content,
                                        btn1: () => {
                                            editor.cm.replaceSelection(`{fwcode type="${$("#fw-alert").val()}"}\n${editor.cm.getSelections()[0]}\n{/fwcode}`);
                                            editor.cm.focus()
                                            layer.close(idx)
                                        }
                                    })
                                }
                            },
                            fwalert: {
                                icon: '<svg t="1639851203504" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="24775" width="48" height="48"><path d="M854.997 767.904l-55.033-92.148V487.875a287.964 287.964 0 1 0-575.928 0v187.753l-55.033 92.276h685.994z m111.218-0.768a63.992 63.992 0 0 1-54.969 96.756H112.754a63.992 63.992 0 0 1-55.033-96.756l70.327-117.873V487.939a383.952 383.952 0 1 1 767.904 0v161.26l70.263 117.937zM512 0q47.994 0 47.994 47.994v95.988q0 47.994-47.994 47.994t-47.994-47.994V47.994Q464.006 0 512 0zM398.862 1024a47.994 47.994 0 1 1 0-95.988H625.65a47.994 47.994 0 0 1 0 95.988H398.862z m65.272-975.174a47.994 47.994 0 0 1 95.988 0v86.645a47.994 47.994 0 0 1-95.988 0V48.826z" fill="#ABB2BF" p-id="24776"></path></svg>',
                                call: function () {
                                    let content = `<div class="fw-layer-content" style="width: 300px;">
                <div class="fw-form-item">
                <label>提示类型：</label>
                <select id="fw-alert-type" style="width: 235px;">
                <option value="success">成功</option>
                <option value="error">失败</option>
                <option value="info">信息</option>
                <option value="warning">警告</option>
                </select>
                </div>
                <div class="fw-form-item"><label>提示内容：</label><textarea style="outline:none;width: 235px;resize: none;height: 100px;" id="fw-alert-content">${editor.cm.getSelections()['0']}</textarea></div>
                </div>
                `
                                    let idx = layer.open({
                                        type: 1,
                                        width: 600,
                                        title: '插入提示',
                                        btn: ['确定', '取消'],
                                        content: content,
                                        btn1: () => {
                                            editor.cm.replaceSelection(`{fwalert type="${$("#fw-alert-type").val()}"}${$("#fw-alert-content").val()}{/fwalert}`);
                                            editor.cm.focus()
                                            layer.close(idx)
                                        }
                                    })
                                }
                            },
                            fwtab: {
                                icon: '<svg t="1639853238185" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="25984" width="48" height="48"><path d="M576 268.8h313.6c12.8 0 19.2-12.8 19.2-25.6V166.4c0-12.8-6.4-25.6-19.2-25.6H518.4c-19.2 0-25.6 25.6-12.8 38.4l57.6 83.2c0 6.4 6.4 6.4 12.8 6.4z" p-id="25985" fill="#ABB2BF"></path><path d="M902.4 320H576c-12.8 0-32-6.4-38.4-25.6L409.6 128c-12.8-12.8-25.6-19.2-44.8-19.2H128c-32 0-57.6 32-57.6 64v627.2c0 38.4 25.6 64 57.6 64h774.4c32 0 57.6-32 57.6-64V384c0-38.4-25.6-64-57.6-64z m-268.8 352c0 19.2-12.8 32-32 32H204.8c-19.2 0-32-12.8-32-32s12.8-32 32-32h403.2c12.8 0 25.6 12.8 25.6 32z m166.4 0c0 19.2-12.8 32-32 32h-25.6c-19.2 0-32-12.8-32-32s12.8-32 32-32h25.6c19.2 0 32 12.8 32 32z" p-id="25986" fill="#ABB2BF"></path></svg>',
                                call: function () {
                                    let content = `<div class="fw-layer-content">
                <div class="fw-form-item">
                <label>tab栏列：</label>
                <input type="number" id="fw-tab-col" value="2">
                </div>
                </div>
                `
                                    let idx = layer.open({
                                        type: 1,
                                        width: 600,
                                        title: '插入tab栏',
                                        btn: ['确定', '取消'],
                                        content: content,
                                        btn1: () => {
                                            let col = $("#fw-tab-col").val()
                                            if (col < 2) {
                                                layer.msg('请至少选择2列', {icon: 2})
                                                return false;
                                            }
                                            col = col < 2 ? 2 : col;
                                            let text = '{fwtab}\n{fwh}\n'
                                            for (let i = 0; i < col; i++) {
                                                text += `{fwthead target="${i + 1}"} tab栏${i + 1} {/fwthead}\n`
                                            }
                                            text += '{/fwh}\n{fwb}\n'
                                            for (let i = 0; i < col; i++) {
                                                text += `{fwtbody target="${i + 1}"}\n内容${i + 1}\n{/fwtbody}\n`
                                            }
                                            text += '{/fwb}\n{/fwtab}'
                                            editor.cm.replaceSelection(text);
                                            editor.cm.focus()
                                            layer.close(idx)
                                        }
                                    })
                                }
                            },
                            fwbtn: {
                                icon: '<svg t="1639903053273" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="30233" width="48" height="48"><path d="M368 518.4c-3.2-6.4-6.4-9.6-12.8-16-3.2-3.2-9.6-6.4-12.8-9.6 3.2-3.2 6.4-3.2 6.4-6.4 3.2-3.2 3.2-6.4 6.4-9.6 0-3.2 3.2-6.4 3.2-12.8v-12.8c0-9.6-3.2-19.2-6.4-28.8-3.2-9.6-9.6-16-12.8-22.4-6.4-6.4-12.8-9.6-19.2-12.8-6.4-3.2-16-3.2-22.4-3.2H224v252.8h80c9.6 0 19.2-3.2 28.8-6.4 9.6-3.2 16-9.6 22.4-16 6.4-6.4 12.8-16 16-25.6 3.2-9.6 6.4-19.2 6.4-32 0-6.4 0-16-3.2-22.4s-3.2-12.8-6.4-16z m-96-89.6h16c6.4 0 12.8 3.2 16 6.4 6.4 3.2 6.4 9.6 6.4 19.2s-3.2 16-6.4 19.2c-3.2 6.4-9.6 6.4-16 6.4h-16v-51.2z m54.4 144c-3.2 3.2-3.2 6.4-6.4 9.6-3.2 3.2-6.4 3.2-9.6 6.4-3.2 0-6.4 3.2-12.8 3.2H272v-64h25.6c9.6 0 16 3.2 22.4 9.6 6.4 6.4 9.6 12.8 9.6 25.6 0 0-3.2 6.4-3.2 9.6zM748.8 480v48c-6.4-6.4-9.6-16-16-25.6-6.4-9.6-12.8-16-19.2-22.4L640 384h-41.6v252.8h51.2v-160l19.2 28.8c6.4 9.6 12.8 19.2 22.4 28.8l83.2 108.8H800v-256h-51.2V480z" fill="#ABB2BF" p-id="30234"></path><path d="M896 224H128c-35.2 0-64 28.8-64 64v448c0 35.2 28.8 64 64 64h768c35.2 0 64-28.8 64-64V288c0-35.2-28.8-64-64-64z m0 480c0 19.2-12.8 32-32 32H160c-19.2 0-32-12.8-32-32V320c0-19.2 12.8-32 32-32h704c19.2 0 32 12.8 32 32v384z" fill="#ABB2BF" p-id="30235"></path><path d="M393.6 432h64v204.8H512V432h64v-48H393.6z" fill="#ABB2BF" p-id="30236"></path></svg>',
                                call: function () {
                                    let content = `<div class="fw-layer-content" style="width: 300px;">
                <div class="fw-form-item">
                <label>按钮类型：</label>
                <select id="fw-btn-type" style="width: 235px;">
                <option value="normal">正常</option>
                <option value="success">成功</option>
                <option value="error">失败</option>
                <option value="info">信息</option>
                <option value="warning">警告</option>
                </select>
                </div>
                <div class="fw-form-item"><label>按钮内容：</label> <input type="text" style="width: 235px;" value="${editor.cm.getSelections()[0]}" id="fw-btn-text"></div>
                <div class="fw-form-item"><label>按钮图标：</label> <input type="text" style="width: 235px;"  id="fw-btn-icon" placeholder="不填写默认为下载图标"></div>
                <div class="fw-form-item"><label>跳转链接：</label> <input type="text" style="width: 235px;"  id="fw-btn-url"></div>
                <p style="margin: 0;padding: 0;font-size: 12px;color: #777;">图标为 <a href="https://fontawesome.dashgame.com/" target="_blank">fontawesome字体图标</a>，拷贝图标名称即可</p>
                </div>
                `
                                    let idx = layer.open({
                                        type: 1,
                                        width: 600,
                                        title: '插入跳转按钮',
                                        btn: ['确定', '取消'],
                                        content: content,
                                        btn1: () => {
                                            let url = $("#fw-btn-url").val();
                                            url = url ? url : '#'
                                            let icon = $("#fw-btn-icon").val();
                                            icon = icon ? icon : 'fa-download';
                                            let btncontent = `{fwbtn type="${$("#fw-btn-type").val()}" url="${url}"}{icon="${icon}"}${$("#fw-btn-text").val()}{/fwbtn}`
                                            editor.cm.replaceSelection(btncontent);
                                            editor.cm.focus()
                                            layer.close(idx)
                                        }
                                    })
                                }
                            },
                            fwgroup: {
                                icon: '<svg t="1639923485430" class="icon" viewBox="0 0 1200 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="26599" width="48" height="48"><path d="M1061.821055 922.722497H139.047995a25.281454 25.281454 0 0 1-25.281453-25.268813V126.407268a25.281454 25.281454 0 0 1 25.281453-25.281453h922.77306a25.281454 25.281454 0 0 1 25.281453 25.281453v771.046416a25.281454 25.281454 0 0 1-25.281453 25.268813z m-897.491606-50.550267h872.210152V151.688722H164.329449v720.483508z" p-id="26600" fill="#ABB2BF"></path><path d="M1175.587596 1023.848311H25.281454a25.281454 25.281454 0 0 1-25.281454-25.281453V25.281454A25.281454 25.281454 0 0 1 25.281454 0.012641h1150.306142a25.281454 25.281454 0 0 1 25.281454 25.268813v973.285404a25.281454 25.281454 0 0 1-25.281454 25.281453z m-1125.024689-50.562907h1099.743236V50.575548H50.562907v922.709856z" p-id="26601" fill="#ABB2BF"></path><path d="M948.054513 594.08888H379.221805a25.281454 25.281454 0 1 1 0-50.562907h568.832708a25.281454 25.281454 0 0 1 0 50.562907zM948.054513 707.842781H379.221805a25.281454 25.281454 0 1 1 0-50.550267h568.832708a25.281454 25.281454 0 1 1 0 50.550267zM948.054513 821.609323H707.880703a25.281454 25.281454 0 0 1 0-50.562908h240.17381a25.281454 25.281454 0 0 1 0 50.562908zM353.409441 271.055106a59.449338 59.449338 0 0 1-76.033972 35.950227 59.487261 59.487261 0 1 1 76.033972-35.950227z" p-id="26602" fill="#ABB2BF"></path><path d="M360.071104 309.495556c-9.809204 11.591547-23.676081 20.768714-40.260715 25.369939-0.139048 0.050563-0.290737 0.075844-0.442425 0.113766a81.343077 81.343077 0 0 1-46.517875-2.730397c-16.18013-5.789453-29.326486-15.902034-38.301402-28.188821l-57.224571 160.006321 0.783725 0.290737 63.709263-47.453289 19.12542 77.057871 2.212127 0.796366 30.312463-84.743433 24.434525 87.638159 0.809007-0.214892 24.624136-75.528343 60.094015 51.902824 2.275331-0.619395-45.633024-163.697413z" p-id="26603" fill="#ABB2BF"></path></svg>',
                                call: function () {
                                    let content = `<div class="fw-layer-content" style="width: 300px;">
                <div class="fw-form-item">
                <label>分组名称：</label>
                <input type="text" id="fw-group-title" style="width: 235px;">
                </div>
                <div class="fw-form-item"><label>分组内容：</label> <textarea style="width: 235px;outline: none;height: 100px;" id="fw-group-cn">${editor.cm.getSelections()[0]}</textarea></div>
                </div>
                `
                                    let idx = layer.open({
                                        type: 1,
                                        width: 600,
                                        title: '插入分组卡片',
                                        btn: ['确定', '取消'],
                                        content: content,
                                        btn1: () => {
                                            let btncontent = `{fwgroup title="${$("#fw-group-title").val()}"}\n${$("#fw-group-cn").val()}\n{/fwgroup}`
                                            editor.cm.replaceSelection(btncontent);
                                            editor.cm.focus()
                                            layer.close(idx)
                                        }
                                    })
                                }
                            },
                            fwbili: {
                                icon: '<svg t="1640000654691" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="18144" width="200" height="200"><path d="M899.982222 233.585778c0-60.512711-49.055289-109.568-109.568-109.568H233.585778c-60.512711 0-109.568 49.055289-109.568 109.568v556.828444c0 60.513849 49.055289 109.568 109.568 109.568h556.828444c60.512711 0 109.568-49.054151 109.568-109.568V233.585778zM716.289138 737.35168c-27.116658-0.857884-36.250738 0-36.250738 0s-1.999076 31.113671-28.543431 31.684836c-26.833351 0.284444-30.830364-21.694009-31.688249-29.97248-16.269084 0-211.805298 0.854471-211.805298 0.854471s-3.424711 28.831289-29.970204 28.831289c-26.833351 0-28.261262-23.976391-29.97248-28.831289-17.414827 0-40.821191-0.571164-40.821191-0.571165s-58.803769-12.270933-66.509938-88.771698c0.854471-76.500764 0-227.793351 0-227.793351s-5.424924-70.504676 64.797582-90.771911c21.691733-0.853333 68.509013-1.143467 122.744605-1.143466l-49.955272-48.525085s-7.705031-9.704107 5.424925-20.55168c13.417813-10.843022 13.985564-6.167893 18.55488-3.024213 4.568178 3.137991 74.502827 72.329671 74.502827 72.329671h-9.419663c26.832213 0 54.522311 0.175218 81.067805 0.175218 10.275271-10.277547 68.793458-67.711431 72.791609-70.566116 4.56704-2.854684 5.422649-7.735751 18.55488 3.110685 13.131093 10.845298 5.423787 20.538027 5.423786 20.538026l-48.81408 47.09376c67.08224 0.571164 118.749867 0.853333 118.749867 0.853334s66.223218 14.556729 67.936711 90.482915c-0.855609 75.930738 0.284444 228.64896 0.284445 228.64896s-3.711431 74.213831-67.083378 85.919289z" p-id="18145" fill="#ABB2BF"></path><path d="M707.439502 387.982222H322.080996C310.377813 387.982222 301.511111 397.376853 301.511111 409.366756V664.849067c0 11.986489 8.866702 21.230933 20.569885 21.230933h385.358506c11.702044 0 20.738276-9.244444 20.738276-21.230933V409.366756c0-11.989902-9.035093-21.384533-20.738276-21.384534z m-359.121351 94.867911l108.604302-20.792889 8.205654 40.759752-107.508623 20.789475-9.301333-40.756338z m167.418311 124.191858c-33.374436 36.384996-68.388409-11.488142-68.388409-11.488142l17.780054-11.490418s23.798898 42.94656 50.334151-13.953707c25.441849 55.257316 53.617778 14.49984 53.617778 14.770632l16.142791 10.397013c-0.002276 0.002276-30.093084 48.150756-69.486365 11.764622z m161.125262-83.43552l-107.781688-20.789475 8.480995-40.759752 108.326685 20.792889-9.025992 40.756338z" p-id="18146" fill="#ABB2BF"></path></svg>',
                                call: function () {
                                    let content = `<div class="fw-layer-content" style="width: 300px;">
                <div class="fw-form-item">
                <label>视频BVID：</label>
                <input type="text" id="fw-bvid" style="width: 220px;">
                </div>
                <div class="fw-form-item">
                <label>视频剧集：</label>
                <input type="number" id="fw-bvnu" value="1" style="width: 220px;">
                </div>
                </div>
                `
                                    let idx = layer.open({
                                        type: 1,
                                        width: 600,
                                        title: '插入B站视频',
                                        btn: ['确定', '取消'],
                                        content: content,
                                        btn1: () => {
                                            let num = $("#fw-bvid").val() ? $("input#fw-bvid").val() : 1;
                                            let btncontent = `{fwbili number="${$("#fw-bvnu").val()}"}\n${num}\n{/fwbili}`
                                            editor.cm.replaceSelection(btncontent);
                                            editor.cm.focus()
                                            layer.close(idx)
                                        }
                                    })
                                }
                            },
                            fwmusic: {
                                icon: '<svg t="1640075445944" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="18356" width="200" height="200"><path d="M512 1024C229.239467 1024 0 794.760533 0 512S229.239467 0 512 0 1024 229.239467 1024 512 794.760533 1024 512 1024z m215.813689-682.552889v-106.0864c0-18.568533-15.746844-32.813511-33.268622-30.037333l-323.265423 51.268266c-13.425778 2.161778-23.369956 14.267733-23.369955 28.512712v338.602666a86.721422 86.721422 0 0 0-32.426667-6.257778c-48.5376 0-87.927467 39.867733-87.927466 89.065245 0 49.220267 39.367111 89.088 87.927466 89.088s87.881956-39.822222 87.881956-89.019733c0-1.547378-0.068267-3.094756-0.136534-4.642134h0.136534V392.942933l268.925155-42.689422v217.224533a87.4496 87.4496 0 0 0-32.540444-6.303288c-48.696889 0-88.064 39.867733-88.064 89.065244 0 49.220267 39.435378 89.088 88.064 89.088 48.605867 0 88.064-39.867733 88.064-89.088 0-2.412089-0.136533-4.824178-0.273067-7.259022V341.515378l0.273067-0.068267z" p-id="18357" fill="#ABB2BF"></path></svg>',
                                call: function () {
                                    let content = `<div class="fw-layer-content" style="width: 300px;">
                <div class="fw-form-item">
                <label>音乐来源：</label>
                <select id="music-source" style="width: 220px;">
                <option value="netease" selected>网易云音乐</option>
                <option value="tencent">腾讯音乐</option>
                </select>
                </div>
                <div class="fw-form-item">
                <label>音乐类型：</label>
                <select id="music-type" style="width: 220px;">
                <option value="playlist" selected>歌单</option>
                <option value="song">歌曲</option>
                </select>
                </div>
                <div class="fw-form-item">
                <label>音乐 &nbsp;I D：</label>
                <input id="music-id" placeholder="歌曲ID/歌单ID" style="width: 220px;">
                </div>
                </div>
                `
                                    let idx = layer.open({
                                        type: 1,
                                        width: 600,
                                        title: '插入音乐',
                                        btn: ['确定', '取消'],
                                        content: content,
                                        btn1: () => {
                                            let ms = $("#music-source").val()
                                            let mt = $("#music-type").val()
                                            let mi = $("#music-id").val()
                                            let btncontent = `{fwmusic source="${ms}" type="${mt}" id="${mi}"}{/fwmusic}`
                                            editor.cm.replaceSelection(btncontent);
                                            editor.cm.focus()
                                            layer.close(idx)
                                        }
                                    })
                                }
                            },
                            colorline: {
                                icon: '<svg t="1640090155960" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="18799" width="200" height="200"><path d="M234.666667 490.666667h-153.6a25.6 25.6 0 1 0 0 51.2h153.6a25.6 25.6 0 1 0 0-51.2zM473.6 490.666667h-153.6a25.6 25.6 0 1 0 0 51.2h153.6a25.6 25.6 0 1 0 0-51.2zM934.4 490.666667h-136.533333a25.6 25.6 0 1 0 0 51.2h136.533333a25.6 25.6 0 1 0 0-51.2zM712.533333 490.666667h-153.6a25.6 25.6 0 1 0 0 51.2h153.6a25.6 25.6 0 1 0 0-51.2z" p-id="18800" fill="#ABB2BF"></path></svg>',
                                call: function () {
                                    let content = `<div class="fw-layer-content" style="width: 300px">
                                                <div class="fw-form-item">
                                                <label>开始颜色：</label>
                                                <input id="color-start" value="#01D0FF" style="margin-right: 10px" >
                                                <div id="start-btn" style="display: inline-block;vertical-align: bottom"></div>
                                                </div>
                                                <div class="fw-form-item">
                                                <label>结束颜色：</label>
                                                <input id="color-end"  value="#FC3E85" style="margin-right: 10px;">
                                                <div id="end-btn" style="display: inline-block;vertical-align: bottom"></div>
                                                </div>
                                                </div>
                                                `
                                    let idx = layer.open({
                                        type: 1,
                                        width: 600,
                                        title: '插入彩色分割符',
                                        btn: ['确定', '取消'],
                                        content: content,
                                        btn1: () => {
                                            let btncontent = `{fwcline start="${$("#color-start").val()}" end="${$("#color-end").val()}"}{/fwcline}`
                                            editor.cm.replaceSelection(btncontent);
                                            editor.cm.focus()
                                            layer.close(idx)
                                        }
                                    })
                                    let colorpicker = layui.colorpicker;
                                    colorpicker.render({
                                        elem: '#start-btn',
                                        color: '#01D0FF',
                                        predefine: true,
                                        colors: ['#01D0FF', '#FC3E85'],
                                        done: function (color) {
                                            $("#color-start").val(color)
                                        }
                                    });
                                    colorpicker.render({
                                        elem: '#end-btn',
                                        color: '#FC3E85',
                                        predefine: true,
                                        colors: ['#01D0FF', '#FC3E85'],
                                        done: function (color) {
                                            $("#color-end").val(color)
                                        }
                                    });
                                }
                            }
                        },

                    })
                })
            })
        });
    }

</script>
