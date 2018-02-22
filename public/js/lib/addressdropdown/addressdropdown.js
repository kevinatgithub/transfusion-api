AddressDropdown = {
    cfg:{
        server:"AddressDropdown",
        region_id:"regcode",
        regcode:null,
        prov_id:"provcode",
        provcode:null,
        city_id:"citycode",
        citycode:null,
        bgy_id:"bgycode",
        bgycode:null    
    },
    config:function(options){
        $.extend(this.cfg,options);
    },
    implement:function(){
        p = this;
        cfg =  p.cfg;
        this.region.reset();
        this.region.populate();

        $("#"+cfg.region_id).change(function(){
            cfg.regcode = $(this).val();
            cfg.provcode = null;
            p.province.reset();
            p.province.populate();
            p.city.reset();
            p.brgy.reset();
        });
        
        $("#"+cfg.prov_id).change(function(){
            cfg.provcode = $(this).val();
            cfg.citycode = null;
            p.city.reset();
            p.city.populate();
            p.brgy.reset();
        });
        
        $("#"+cfg.city_id).change(function(){
            p.citycode = $(this).val();
            cfg.bgycode = null;
            p.brgy.reset();
            p.brgy.populate();
        });
    },
    region:{
        cfg:this.cfg,
        reset:function(){
            $("#"+cfg.region_id).html("<option value=''>Please Select</option>");
        },
        populate:function(){
            if($("#region_loading").length == 0){
                $("#"+cfg.region_id);//.after("<img src='img/loading.gif' valign='middle' id='region_loading' style='margin-left:10px;width:20px;' />");
            }
            $.post(cfg.server+'/reglist',{
            },function(d){
                r = $.parseJSON(d);
                r.data.forEach(function(i){
                    if($("#"+cfg.region_id).find("optin[value='"+i.regcode+"']").length == 0){
                        $("#"+cfg.region_id).append("<option value='"+i.regcode+"'>"+i.regname+"</option>");
                    }
                });
                if(cfg.regcode!=null){
                    $("#"+cfg.region_id).find("option[value*='"+cfg.regcode+"']").attr("SELECTED","SELECTED");
                    p.province.reset();
                    p.province.populate();
                    p.city.reset();
                    p.brgy.reset();
                }
                $("#region_loading").remove();
                
            });
        }
    },
    province:{
        cfg:this.cfg,
        reset:function(){
            $("#"+cfg.prov_id).html("<option value=''>Please Select</option>");
        },
        populate:function(){
            if($("#province_loading").length == 0){
                $("#"+cfg.prov_id);//.after("<img src='img/loading.gif' valign='middle' id='province_loading' style='margin-left:10px;width:20px;' />");
            }
            $.post(cfg.server+'/provlist',{
                regcode:$("#"+cfg.region_id).val()
            },function(d){
                r = $.parseJSON(d);
                r.data.forEach(function(i){
                    if($("#"+cfg.prov_id).find("option[value='"+i.provcode+"']").length == 0){
                        $("#"+cfg.prov_id).append("<option value='"+i.provcode+"'>"+i.provname+"</option>");
                    }
                });
                if(cfg.provcode!=null){
                    $("#"+cfg.prov_id).find("option[value*='"+cfg.provcode+"']").attr("SELECTED","SELECTED");
                    p.city.reset();
                    p.city.populate();
                    p.brgy.reset();
                }
                $("#province_loading").remove();
                
            });
        }
    },
    city:{
        cfg:this.cfg,
        reset:function(){
            $("#"+cfg.city_id).html("<option value=''>Please Select</option>");
        },
        populate:function(){
            if($("#city_loading").length == 0){
                $("#"+cfg.city_id);//.after("<img src='img/loading.gif' valign='middle' id='city_loading' style='margin-left:10px;width:20px;' />");
            }
            $.post(cfg.server+'/citylist',{
                provcode:$("#"+cfg.prov_id).val()
            },function(d){
                r = $.parseJSON(d);
                r.data.forEach(function(i){
                    if($("#"+cfg.city_id).find("option[value='"+i.citycode+"']").length == 0){
                        $("#"+cfg.city_id).append("<option value='"+i.citycode+"'>"+i.cityname+"</option>");
                    }
                });
                if(cfg.citycode!=null){
                    $("#"+cfg.city_id).find("option[value*='"+cfg.citycode+"']").attr("SELECTED","SELECTED");
                    p.brgy.reset();
                    p.brgy.populate();
                }
                $("#city_loading").remove();
                
            });
        }
    },
    brgy:{
        cfg:this.cfg,
        reset:function(){
            $("#"+cfg.bgy_id).html("<option value=''>Please Select</option>");
        },
        populate:function(){
            if($("#brgy_loading").length == 0){
                $("#"+cfg.bgy_id);//.after("<img src='img/loading.gif' valign='middle' id='brgy_loading' style='margin-left:10px;width:20px;' />");
            }
            $.post(cfg.server+'/brgylist',{
                citycode:$("#"+cfg.city_id).val()
            },function(d){
                r = $.parseJSON(d);
                r.data.forEach(function(i){
                    if($("#"+cfg.bgy_id).find("option[value='"+i.bgycode+"']").length == 0){
                        $("#"+cfg.bgy_id).append("<option value='"+i.bgycode+"'>"+i.bgyname+"</option>");
                    }
                });
                if(cfg.bgycode!=null){
                    $("#"+cfg.bgy_id).find("option[value*='"+cfg.bgycode+"']").attr("SELECTED","SELECTED");
                }
                $("#brgy_loading").remove();
                
            });
        }
    }
};
