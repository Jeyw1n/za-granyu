from ultralytics import YOLO

def detect_objects(image_path):
    model = YOLO('weights.pt')
    results = model.predict(source=image_path, conf=0.25)

    detections = []
    for result in results:
        for box in result.boxes:
            x1, y1, x2, y2 = box.xyxy[0]  # Координаты бокса
            label = result.names[int(box.cls)]  # Имя класса
            detections.append((label, x1))  # Сохраняем лейбл и координату x1

    # Сортируем по координате X (x1)
    detections.sort(key=lambda x: x[1])  # Сортируем по x1

    # Извлекаем только лейблы и преобразуем в строку
    sorted_labels = ' '.join(label for label, _ in detections)

    return sorted_labels

if __name__ == "__main__":
    image_path = input('Путь к изображению: ')
    print("Обнаруженные лейблы:", detect_objects(image_path))
