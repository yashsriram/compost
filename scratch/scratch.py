from keras.layers import Embedding
from keras.models import Sequential
import numpy as np

model = Sequential()
model.add(Embedding(1000, 64, input_length=10))
# the model will take as input an integer matrix of size (batch, input_length).
# the largest integer (i.e. word index) in the input should be no larger than 999 (vocabulary size).
# now model.output_shape == (None, 10, 64), where None is the batch dimension.

input_array = np.random.randint(1000, size=(32, 1))
print(input_array)

model.compile('rmsprop', 'mse')
output_array = model.predict(input_array)
print(output_array.shape)
