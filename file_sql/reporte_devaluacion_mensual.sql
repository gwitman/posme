SET @v1 = 0;

select 
	cur.name as Cordoba,
	c.date as Fecha, 
	c.ratio as TipoCambio ,
	targ.name as Dolar,
	round(IF(@v1 = 0,0,c.ratio - @v1),5)  as DevaluacionMensual,
	round(@v1:=c.ratio,5) as Asignacion 
from 
	tb_exchange_rate c 
	inner join tb_currency cur on 
		c.currencyID = cur.currencyID 
	inner join tb_currency targ on 
		c.targetCurrencyID = targ.currencyID 
where 
	dayofmonth(c.date) = 1 
order by 
	c.date desc 
limit 
	 12; 