-- #获取18年以后， hot_post里最多的user top 1000
SELECT count(id) as ct, userid FROM hot_post WHERE postcreate_dateline > "2018-1-1" GROUP BY userid ORDER BY ct DESC LIMIT 1000
-- 连表查询 最少的文章数为7
SELECT count(hp.id) as ct, hp.userid, hp.blogid, blg.username FROM hot_post as hp, blogger as blg, blogger_info as blgi WHERE hp.postcreate_dateline > "2018-1-1" AND hp.blogid = blg.blogid AND blgi.blogger_info_id = blg.blogid AND blgi.blogger_info_date_of_last_activity > "2020-7-1" GROUP BY userid ORDER BY ct DESC LIMIT 1000

-- hot blogger 还活跃的用户
SELECT hb.* FROM hot_blogger as hb, blogger_info as blgi WHERE blgi.blogger_info_date_of_last_activity > "2020-1-1" and hb.blogid = blgi.blogger_info_id
-- hot blogger 还活跃的用户 500人
SELECT hb.blogid FROM hot_blogger as hb, blogger_info as blgi WHERE blgi.blogger_info_date_of_last_activity > "2020-1-1" and hb.blogid = blgi.blogger_info_id

-- 不重叠的人数为133
SELECT hb.blogid FROM hot_blogger as hb, blogger_info as blgi WHERE blgi.blogger_info_date_of_last_activity > "2020-1-1" and hb.blogid = blgi.blogger_info_id AND hb.blogid not in (select blogid from (SELECT count(hp.id) as ct, hp.blogid FROM hot_post as hp, blogger as blg, blogger_info as blgi WHERE hp.postcreate_dateline > "2018-1-1" AND hp.blogid = blg.blogid AND blgi.blogger_info_id = blg.blogid AND blgi.blogger_info_date_of_last_activity > "2020-7-1" GROUP BY hp.blogid ORDER BY ct DESC LIMIT 1000) as tmp)
 
SELECT count(hb.blogid) FROM hot_blogger as hb, blogger_info as blgi WHERE blgi.blogger_info_date_of_last_activity > "2020-1-1" and hb.blogid = blgi.blogger_info_id AND hb.blogid not in (select blogid from (SELECT count(hp.id) as ct, hp.blogid FROM hot_post as hp, blogger as blg, blogger_info as blgi WHERE hp.postcreate_dateline > "2018-1-1" AND hp.blogid = blg.blogid AND blgi.blogger_info_id = blg.blogid AND blgi.blogger_info_date_of_last_activity > "2020-7-1" GROUP BY hp.blogid ORDER BY ct DESC LIMIT 1000) as tmp)
--  总数约为1133


SELECT count(*) FROM `blogger` WHERE blogid not in (Select blogger_info_id from blogger_info as blgi where blgi.blogger_info_date_of_last_activity > "2018-1-1")