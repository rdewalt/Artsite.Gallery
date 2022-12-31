import pymysql
  
def lambda_handler(event, context):
    conn = pymysql.connect(
        host='mysql',
        user='yna', 
        password = "86753091024",
        db='yna',
        )
    cur = conn.cursor()
    cur.execute("select count(*),ImageID from yna.image_faves group by ImageID")
    output = cur.fetchall()
    for x,y in output:
        print("{} - {}".format(x,y))
        cur.execute("update images set FaveCount={} where ImageID={}".format(x,y))
    cur.execute("select count(*), ImageID from yna.image_views group by ImageID")
    output = cur.fetchall()
    for x,y in output:
        print("{} - {}".format(x,y))
        cur.execute("update images set ViewCount={} where ImageID={}".format(x,y))      
    conn.close()
    return "Success"