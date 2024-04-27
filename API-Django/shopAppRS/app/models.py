from django.db import models

class UserData(models.Model):
    id = models.AutoField(primary_key=True)
    id_user = models.IntegerField()
    recent_care = models.TextField(null=True, blank=True)
    recent_add = models.TextField(null=True, blank=True)
    recent_buy = models.TextField(null=True, blank=True)

    class Meta:
        db_table = 'user_datas'