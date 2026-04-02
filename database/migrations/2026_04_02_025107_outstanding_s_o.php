<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('outstanding_so_t_data1', function (Blueprint $table) {
            $table->id();

            $table->text('mandt')->nullable();
            $table->text('kunnr')->nullable()->index();
            $table->text('name1')->nullable();
            $table->text('auart')->nullable()->index();
            $table->text('auart2')->nullable();
            $table->text('types')->nullable();
            $table->text('vbeln')->nullable()->index();
            $table->text('posnr')->nullable()->index();

            $table->decimal('netpr', 18, 2)->nullable();
            $table->decimal('netwr', 18, 2)->nullable();
            $table->decimal('totpr', 18, 2)->nullable();
            $table->decimal('totpr2', 18, 2)->nullable();

            $table->text('waerk')->nullable();
            $table->date('edatu')->nullable()->index();
            $table->text('werks')->nullable()->index();
            $table->text('bstnk')->nullable();

            $table->decimal('kwmeng', 18, 3)->nullable();
            $table->decimal('bmeng', 18, 3)->nullable();

            $table->text('vrkme')->nullable();
            $table->text('meins')->nullable();

            $table->text('matnr')->nullable()->index();
            $table->text('maktx')->nullable();

            $table->decimal('kalab', 18, 3)->nullable();
            $table->decimal('kalab2', 18, 3)->nullable();
            $table->decimal('qty_delivery', 18, 3)->nullable();
            $table->decimal('qty_gi', 18, 3)->nullable();
            $table->decimal('qty_balance', 18, 3)->nullable();
            $table->decimal('qty_balance2', 18, 3)->nullable();
            $table->decimal('mengx1', 18, 3)->nullable();
            $table->decimal('mengx2', 18, 3)->nullable();
            $table->decimal('menge', 18, 3)->nullable();
            $table->decimal('assy', 18, 3)->nullable();
            $table->decimal('paint', 18, 3)->nullable();
            $table->decimal('packg', 18, 3)->nullable();
            $table->decimal('qtys', 18, 3)->nullable();
            $table->decimal('ebdin', 18, 3)->nullable();
            $table->decimal('machp', 18, 3)->nullable();
            $table->decimal('ebdip', 18, 3)->nullable();

            $table->text('type1')->nullable();
            $table->text('type2')->nullable();
            $table->text('type')->nullable();
            $table->integer('dayx')->nullable();

            $table->decimal('machi', 18, 3)->nullable();
            $table->decimal('cutt', 18, 3)->nullable();
            $table->decimal('assym', 18, 3)->nullable();
            $table->decimal('assymt', 18, 3)->nullable();
            $table->decimal('primer', 18, 3)->nullable();
            $table->decimal('paintm', 18, 3)->nullable();
            $table->decimal('paintmt', 18, 3)->nullable();
            $table->decimal('packgm', 18, 3)->nullable();

            $table->decimal('prsm', 18, 2)->nullable();
            $table->decimal('prsm2', 18, 2)->nullable();

            $table->decimal('qprom', 18, 3)->nullable();
            $table->decimal('qodrm', 18, 3)->nullable();
            $table->decimal('qproa', 18, 3)->nullable();
            $table->decimal('qodra', 18, 3)->nullable();
            $table->decimal('prsa', 18, 2)->nullable();
            $table->decimal('qproi', 18, 3)->nullable();
            $table->decimal('qodri', 18, 3)->nullable();
            $table->decimal('prsi', 18, 2)->nullable();
            $table->decimal('qprop', 18, 3)->nullable();
            $table->decimal('qpdrp', 18, 3)->nullable();
            $table->decimal('prsp', 18, 2)->nullable();
            $table->decimal('qproc', 18, 3)->nullable();
            $table->decimal('qodrc', 18, 3)->nullable();
            $table->decimal('prsc', 18, 2)->nullable();
            $table->decimal('qproam', 18, 3)->nullable();
            $table->decimal('qodram', 18, 3)->nullable();
            $table->decimal('prsam', 18, 2)->nullable();
            $table->decimal('qproir', 18, 3)->nullable();
            $table->decimal('qodrir', 18, 3)->nullable();
            $table->decimal('prsir', 18, 2)->nullable();
            $table->decimal('qproimt', 18, 3)->nullable();
            $table->decimal('qodrimt', 18, 3)->nullable();
            $table->decimal('prsimt', 18, 2)->nullable();

            $table->text('name4')->nullable();
            $table->integer('kmtl')->nullable();

            $table->timestamps();
        });

        Schema::create('outstanding_so_t_data2', function (Blueprint $table) {
            $table->id();

            $table->text('mandt')->nullable();
            $table->text('kunnr')->nullable()->index();
            $table->text('name1')->nullable();
            $table->text('auart')->nullable()->index();
            $table->text('auart2')->nullable();
            $table->text('types')->nullable();
            $table->text('vbeln')->nullable()->index();
            $table->text('posnr')->nullable()->index();

            $table->decimal('netpr', 18, 2)->nullable();
            $table->decimal('netwr', 18, 2)->nullable();
            $table->decimal('totpr', 18, 2)->nullable();
            $table->decimal('totpr2', 18, 2)->nullable();

            $table->text('waerk')->nullable();
            $table->date('edatu')->nullable()->index();
            $table->text('werks')->nullable()->index();
            $table->text('bstnk')->nullable();

            $table->decimal('kwmeng', 18, 3)->nullable();
            $table->decimal('bmeng', 18, 3)->nullable();

            $table->text('vrkme')->nullable();
            $table->text('meins')->nullable();

            $table->text('matnr')->nullable()->index();
            $table->text('maktx')->nullable();

            $table->decimal('kalab', 18, 3)->nullable();
            $table->decimal('kalab2', 18, 3)->nullable();
            $table->decimal('qty_delivery', 18, 3)->nullable();
            $table->decimal('qty_gi', 18, 3)->nullable();
            $table->decimal('qty_balance', 18, 3)->nullable();
            $table->decimal('qty_balance2', 18, 3)->nullable();
            $table->decimal('mengx1', 18, 3)->nullable();
            $table->decimal('mengx2', 18, 3)->nullable();
            $table->decimal('menge', 18, 3)->nullable();
            $table->decimal('assy', 18, 3)->nullable();
            $table->decimal('paint', 18, 3)->nullable();
            $table->decimal('packg', 18, 3)->nullable();
            $table->decimal('qtys', 18, 3)->nullable();
            $table->decimal('ebdin', 18, 3)->nullable();
            $table->decimal('machp', 18, 3)->nullable();
            $table->decimal('ebdip', 18, 3)->nullable();

            $table->text('type1')->nullable();
            $table->text('type2')->nullable();
            $table->text('type')->nullable();
            $table->integer('dayx')->nullable();

            $table->decimal('machi', 18, 3)->nullable();
            $table->decimal('cutt', 18, 3)->nullable();
            $table->decimal('assym', 18, 3)->nullable();
            $table->decimal('assymt', 18, 3)->nullable();
            $table->decimal('primer', 18, 3)->nullable();
            $table->decimal('paintm', 18, 3)->nullable();
            $table->decimal('paintmt', 18, 3)->nullable();
            $table->decimal('packgm', 18, 3)->nullable();

            $table->decimal('prsm', 18, 2)->nullable();
            $table->decimal('prsm2', 18, 2)->nullable();

            $table->decimal('qprom', 18, 3)->nullable();
            $table->decimal('qodrm', 18, 3)->nullable();
            $table->decimal('qproa', 18, 3)->nullable();
            $table->decimal('qodra', 18, 3)->nullable();
            $table->decimal('prsa', 18, 2)->nullable();
            $table->decimal('qproi', 18, 3)->nullable();
            $table->decimal('qodri', 18, 3)->nullable();
            $table->decimal('prsi', 18, 2)->nullable();
            $table->decimal('qprop', 18, 3)->nullable();
            $table->decimal('qpdrp', 18, 3)->nullable();
            $table->decimal('prsp', 18, 2)->nullable();
            $table->decimal('qproc', 18, 3)->nullable();
            $table->decimal('qodrc', 18, 3)->nullable();
            $table->decimal('prsc', 18, 2)->nullable();
            $table->decimal('qproam', 18, 3)->nullable();
            $table->decimal('qodram', 18, 3)->nullable();
            $table->decimal('prsam', 18, 2)->nullable();
            $table->decimal('qproir', 18, 3)->nullable();
            $table->decimal('qodrir', 18, 3)->nullable();
            $table->decimal('prsir', 18, 2)->nullable();
            $table->decimal('qproimt', 18, 3)->nullable();
            $table->decimal('qodrimt', 18, 3)->nullable();
            $table->decimal('prsimt', 18, 2)->nullable();

            $table->text('name4')->nullable();
            $table->integer('kmtl')->nullable();

            $table->timestamps();
        });

        Schema::create('outstanding_so_t_data3', function (Blueprint $table) {
            $table->id();

            $table->text('mandt')->nullable();
            $table->text('kunnr')->nullable()->index();
            $table->text('name1')->nullable();
            $table->text('auart')->nullable()->index();
            $table->text('auart2')->nullable();
            $table->text('types')->nullable();
            $table->text('vbeln')->nullable()->index();
            $table->text('posnr')->nullable()->index();

            $table->decimal('netpr', 18, 2)->nullable();
            $table->decimal('netwr', 18, 2)->nullable();
            $table->decimal('totpr', 18, 2)->nullable();
            $table->decimal('totpr2', 18, 2)->nullable();

            $table->text('waerk')->nullable();
            $table->date('edatu')->nullable()->index();
            $table->text('werks')->nullable()->index();
            $table->text('bstnk')->nullable();

            $table->decimal('kwmeng', 18, 3)->nullable();
            $table->decimal('bmeng', 18, 3)->nullable();

            $table->text('vrkme')->nullable();
            $table->text('meins')->nullable();

            $table->text('matnr')->nullable()->index();
            $table->text('maktx')->nullable();

            $table->decimal('kalab', 18, 3)->nullable();
            $table->decimal('kalab2', 18, 3)->nullable();
            $table->decimal('qty_delivery', 18, 3)->nullable();
            $table->decimal('qty_gi', 18, 3)->nullable();
            $table->decimal('qty_balance', 18, 3)->nullable();
            $table->decimal('qty_balance2', 18, 3)->nullable();
            $table->decimal('mengx1', 18, 3)->nullable();
            $table->decimal('mengx2', 18, 3)->nullable();
            $table->decimal('menge', 18, 3)->nullable();
            $table->decimal('assy', 18, 3)->nullable();
            $table->decimal('paint', 18, 3)->nullable();
            $table->decimal('packg', 18, 3)->nullable();
            $table->decimal('qtys', 18, 3)->nullable();
            $table->decimal('ebdin', 18, 3)->nullable();
            $table->decimal('machp', 18, 3)->nullable();
            $table->decimal('ebdip', 18, 3)->nullable();

            $table->text('type1')->nullable();
            $table->text('type2')->nullable();
            $table->text('type')->nullable();
            $table->integer('dayx')->nullable();

            $table->decimal('machi', 18, 3)->nullable();
            $table->decimal('cutt', 18, 3)->nullable();
            $table->decimal('assym', 18, 3)->nullable();
            $table->decimal('assymt', 18, 3)->nullable();
            $table->decimal('primer', 18, 3)->nullable();
            $table->decimal('paintm', 18, 3)->nullable();
            $table->decimal('paintmt', 18, 3)->nullable();
            $table->decimal('packgm', 18, 3)->nullable();

            $table->decimal('prsm', 18, 2)->nullable();
            $table->decimal('prsm2', 18, 2)->nullable();

            $table->decimal('qprom', 18, 3)->nullable();
            $table->decimal('qodrm', 18, 3)->nullable();
            $table->decimal('qproa', 18, 3)->nullable();
            $table->decimal('qodra', 18, 3)->nullable();
            $table->decimal('prsa', 18, 2)->nullable();
            $table->decimal('qproi', 18, 3)->nullable();
            $table->decimal('qodri', 18, 3)->nullable();
            $table->decimal('prsi', 18, 2)->nullable();
            $table->decimal('qprop', 18, 3)->nullable();
            $table->decimal('qpdrp', 18, 3)->nullable();
            $table->decimal('prsp', 18, 2)->nullable();
            $table->decimal('qproc', 18, 3)->nullable();
            $table->decimal('qodrc', 18, 3)->nullable();
            $table->decimal('prsc', 18, 2)->nullable();
            $table->decimal('qproam', 18, 3)->nullable();
            $table->decimal('qodram', 18, 3)->nullable();
            $table->decimal('prsam', 18, 2)->nullable();
            $table->decimal('qproir', 18, 3)->nullable();
            $table->decimal('qodrir', 18, 3)->nullable();
            $table->decimal('prsir', 18, 2)->nullable();
            $table->decimal('qproimt', 18, 3)->nullable();
            $table->decimal('qodrimt', 18, 3)->nullable();
            $table->decimal('prsimt', 18, 2)->nullable();

            $table->text('name4')->nullable();
            $table->integer('kmtl')->nullable();

            $table->timestamps();
        });

        Schema::create('outstanding_so_t_data4', function (Blueprint $table) {
            $table->id();

            $table->text('mandt')->nullable();
            $table->text('sel')->nullable();
            $table->text('indct')->nullable();
            $table->text('auart')->nullable()->index();
            $table->text('aufnr')->nullable()->index();

            $table->date('gltrp')->nullable();
            $table->date('gstrp')->nullable();

            $table->text('aufpl')->nullable();
            $table->text('aplzl')->nullable();

            $table->decimal('psmng', 18, 3)->nullable();
            $table->decimal('wemng', 18, 3)->nullable();

            $table->text('kdauf')->nullable()->index();
            $table->text('kdpos')->nullable()->index();

            $table->text('name1')->nullable();
            $table->text('kunnr')->nullable()->index();
            $table->text('amein')->nullable();

            $table->text('matnr')->nullable()->index();
            $table->text('maktx')->nullable();
            $table->text('matfg')->nullable();
            $table->text('makfg')->nullable();

            $table->decimal('kwmeng', 18, 3)->nullable();
            $table->text('vrkme')->nullable();
            $table->decimal('bmeng', 18, 3)->nullable();

            $table->text('werks')->nullable()->index();
            $table->text('lgort')->nullable();
            $table->text('dispo')->nullable();
            $table->text('stats')->nullable();
            $table->text('stats2')->nullable();
            $table->text('objnr')->nullable();
            $table->text('objnr2')->nullable();
            $table->text('meins')->nullable();
            $table->text('rsnum')->nullable();
            $table->text('baugr')->nullable();
            $table->text('matkl')->nullable();
            $table->text('wgbez')->nullable();

            $table->decimal('p1', 18, 3)->nullable();
            $table->decimal('meng2', 18, 3)->nullable();
            $table->decimal('qty_delivery', 18, 3)->nullable();
            $table->decimal('qty_gi', 18, 3)->nullable();
            $table->decimal('qty_balance', 18, 3)->nullable();
            $table->decimal('qty_balance2', 18, 3)->nullable();

            $table->text('action')->nullable();

            $table->integer('prsn')->nullable();
            $table->integer('prsn2')->nullable();

            $table->decimal('tottp', 18, 2)->nullable();
            $table->decimal('totreq', 18, 2)->nullable();

            $table->date('edatu')->nullable();
            $table->text('plnum')->nullable();
            $table->text('pwwrk')->nullable();
            $table->decimal('gsmng', 18, 3)->nullable();
            $table->date('psttr')->nullable();
            $table->date('pedtr')->nullable();

            $table->text('plnty')->nullable();
            $table->text('plnnr')->nullable();
            $table->text('arbid')->nullable();
            $table->text('arbpl')->nullable();
            $table->text('steus')->nullable();
            $table->text('vornr')->nullable();

            $table->decimal('vgw01', 18, 3)->nullable();
            $table->text('vge01')->nullable();
            $table->decimal('bmsch', 18, 3)->nullable();
            $table->decimal('enmng', 18, 3)->nullable();
            $table->decimal('bdmng', 18, 3)->nullable();
            $table->decimal('kalab', 18, 3)->nullable();

            $table->text('sobsl')->nullable();
            $table->text('beskz')->nullable();
            $table->text('ltext')->nullable();
            $table->decimal('splim', 18, 0)->nullable();
            $table->text('cpcty')->nullable();
            $table->text('cpctyx')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outstanding_so_t_data1');
        Schema::dropIfExists('outstanding_so_t_data2');
        Schema::dropIfExists('outstanding_so_t_data3');
        Schema::dropIfExists('outstanding_so_t_data4');
    }
};
