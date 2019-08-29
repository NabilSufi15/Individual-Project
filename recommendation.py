
# coding: utf-8

# In[10]:


import pandas as pd
import graphlab


# In[11]:


#Reading ratings file:
ratings = pd.read_csv('D:/wamp64/www/FYP/results.csv')


# In[12]:


#converts the rating into SFrame so it can be computed
rating_data = graphlab.SFrame(ratings)


# In[13]:


#creates the model for the item similariy model
item_sim_model = graphlab.item_similarity_recommender.create(rating_data, user_id='user_id', item_id='game_name', target='ratings', similarity_type='pearson')

#Creates the reocmmendation list for the users:
item_sim_recomm = item_sim_model.recommend(users=range(1,6),k=3)
item_sim_recomm.print_rows(num_rows=25)
item_sim_recomm.export_csv('D:/wamp64/www/FYP/recom.csv')


# In[ ]:





# In[ ]:





# In[ ]:





# In[ ]:





# In[ ]:





# In[ ]:





# In[ ]:





# In[ ]:





# In[ ]:





# In[ ]:





# In[ ]:





# In[ ]:




