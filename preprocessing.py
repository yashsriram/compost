import csv
import re
import numpy as np
from keras.preprocessing.sequence import pad_sequences
from keras.preprocessing.text import Tokenizer


def preprocess_essay_data(filepath, max_length=2000):
    """
    loads whole data and labels into memory
    """
    data = []
    labels = []
    with open(filepath) as f:
        tsv = csv.reader(f, delimiter="\t", quotechar='"')
        # skip header
        next(tsv, None)
        for values in tsv:
            essay = values[2]
            normalized_score = values[11]
            data.append(re.sub('(@\w+)', '', essay))
            labels.append(normalized_score)

    data = np.array(data)
    labels = np.array(labels)

    # prepare tokenizer
    tokenizer = Tokenizer()
    tokenizer.fit_on_texts(data)

    # integer encode data
    encoded_data = tokenizer.texts_to_sequences(data)

    # pad data to a max length
    padded_data = pad_sequences(encoded_data, maxlen=max_length, padding='post')

    # vocabulary size
    vocabulary_size = len(tokenizer.word_index) + 1

    print('---- ---- encoded data and labels')
    print('data shape = ', padded_data.shape, ' labels shape = ', labels.shape, 'vocabulary_size = ', vocabulary_size)

    return padded_data, labels, vocabulary_size, tokenizer.word_index


def load_word_embeddings(filepath):
    """
    loads the whole embedding into memory
    """
    word_embeddings = dict()
    with open(filepath) as f:
        for line in f:
            values = line.split()
            word = values[0]
            word_embedding = np.array(values[1:], dtype='float32')
            word_embeddings[word] = word_embedding

    print('---- ---- loaded word embeddings into memory')
    print('no. of word embeddings = ', len(word_embeddings))

    return word_embeddings


def get_word_embeddings_matrix(word_embeddings, vocabulary_size, word_index):
    embeddings_matrix = np.zeros((vocabulary_size, 300))
    spelling_mistakes = []
    for word, index in word_index.items():
        embedding = word_embeddings.get(word)
        if embedding is None:
            embeddings_matrix[index] = 0  # default representation
            spelling_mistakes.append(word)
        else:
            embeddings_matrix[index] = embedding

    print('---- ---- created embeddings matrix')
    print('embeddings matrix shape =', embeddings_matrix.shape)
    print('found #spelling mistakes =', len(spelling_mistakes))
    print('%of words not found in word word embeddings =', len(spelling_mistakes) / vocabulary_size * 100)

    return embeddings_matrix
