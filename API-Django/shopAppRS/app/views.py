from rest_framework import viewsets, generics, status
from .models import UserData
from .serializers import UserDataSerializer
from rest_framework.views import APIView
from rest_framework.response import Response

import numpy as np


class UserDataViewSet(viewsets.ModelViewSet):
    queryset = UserData.objects.all()
    serializer_class = UserDataSerializer

import ast 
class RecommenderSystem(APIView):
    def get(self, request):
        user_id = request.GET.get('id_user')
        
        if user_id:
            
            a, all_users, all_items, bought = create_matrix()

            print('ID USERS:\n', all_users)
            print('ID ITEMS:\n', all_items)
            print('MATRIX INIT:\n', a)
            
            K = 2  #@param {type:""}

            #leaning_rate
            beta = 0.01 #@param {type:""}

            #regularization
            lamda = 0.02 #@param {type:""}

            epos = 400 #@param {type:""}

            a = matrix_factorization_upgare(a, K, beta, lamda, epos)

            print('MATRIX RATING RESULT:\n', np.round(a,2))

            index_user = 0
            vec = []
            for id_user in all_users:
                if (str(id_user) == user_id):
                    
                    index_item = 0
                    
                    for id_item in all_items:

                        vec.append((a[index_user][index_item], id_item))
                        index_item += 1
                    
                    break
                index_user += 1
            
            sorted_vec = sorted(vec, key=lambda x: x[0], reverse=True) 
            
            items = [t[1] for t in sorted_vec if t[1] not in bought[id_user]]
            
            data = {
                'recommend_products': items[:min(6,len(items))],
            }
            return Response(data, status=status.HTTP_200_OK)

        else:
            return Response("need id_user")

        


def matrix_factorization_upgare(a, K, beta, lamda, epos):
  max_loss_increase = 20    
  prev_loss = None
  users, items = a.shape

  W = np.random.rand(users, K)
  H = np.random.rand(items, K)

  #upgare
  _u = np.nanmean(a)

  bias_users = []
  bias_items = []
  for u in range(users):
    bias_users.append(np.nanmean(a[u, :]) - _u)

  for i in range(items):
    bias_items.append(np.nanmean(a[:, i]) - _u)
  #######

  a[np.isnan(a)] = 0
  # Training
  for step in range(epos):
      for u in range(users):
          for i in range(items):
              if a[u, i] > 0:

                  aui = _u + bias_users[u] + bias_items[i] + np.dot(W[u, :], H[i, :])

                  error = a[u, i] - aui
                  _u = _u + beta * error
                  bias_users[u] = bias_users[u] + beta * (error - lamda * bias_users[u])
                  bias_items[i] = bias_items[i] + beta * (error - lamda * bias_items[i])

                  for k in range(K):

                      W[u, k] += beta * (error * H[i, k] - lamda * W[u, k])
                      H[i, k] += beta * (error * W[u, k] - lamda * H[i, k])
      print('epos',step,'loss:',error)
      if (error < 0.1):
          print("error < 0.1 -> EXIT FAST", error)
          break
          # Kiểm tra sự tăng bất thường của loss
      if prev_loss is not None and error > prev_loss:
            loss_increase_count += 1
            if loss_increase_count >= max_loss_increase and epos > 100 and error < 0.2:
                print("Loss tăng bất thường, dừng sớm quá trình huấn luyện.")
                break
      else:
            loss_increase_count = 0  # Reset lại số lượng các bước tăng loss liên tiếp
      prev_loss = error  # Cập nhật giá trị loss trước đó
  matrix = np.dot(W, H.T)
  for u in range(users):
    for i in range(items):
      matrix[u][i] += _u + bias_users[u] + bias_items[i]
  return matrix
    



def create_matrix():
    user_data = UserData.objects.all()
    
    # Tạo một set chứa tất cả các mã hàng hóa
    all_users = []
    all_items = set()
    rating = {}
    bought = {}

    for data in user_data:
        all_users.append(data.id_user)
        rating.setdefault(data.id_user, {})
        bought.setdefault(data.id_user, {})

        #Chuyển xâu thành list bỏ kí tự []
        recent_care = ast.literal_eval(data.recent_care)
        recent_add = ast.literal_eval(data.recent_add)
        recent_buy = ast.literal_eval(data.recent_buy)

        for item in recent_care:
            rating[data.id_user][item] = 1

        for item in recent_add:
            rating[data.id_user][item] = 2

        for item in recent_buy:
            rating[data.id_user][item] = 5
            bought[data.id_user][item] = True

        all_items.update(recent_care)
        all_items.update(recent_add)
        all_items.update(recent_buy)

    
    a = []
    for data in user_data:
        vec = []
        for item in all_items:
            if item not in rating[data.id_user]:
                val = np.nan
            else:
                val = rating[data.id_user][item]
            
            vec.append(val)
        a.append(vec)
    
    return np.array(a), all_users, all_items, bought      
    