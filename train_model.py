import pandas as pd
from sklearn.tree import DecisionTreeClassifier
from sklearn.preprocessing import LabelEncoder
import joblib

# Load dataset
df = pd.read_csv("loan_dataset.csv")

# Create separate encoders
employment_encoder = LabelEncoder()
eligible_encoder = LabelEncoder()

# Convert Employment to numbers
df["Employment"] = employment_encoder.fit_transform(
    df["Employment"]
)

# Convert Eligible target to numbers
df["Eligible"] = eligible_encoder.fit_transform(
    df["Eligible"]
)

# Features
X = df[
    [
        "Income",
        "Credit Score",
        "Loan Amount",
        "Age",
        "Employment"
    ]
]

# Target
y = df["Eligible"]

# Create ML model
model = DecisionTreeClassifier(
    random_state=42
)

# Train model
model.fit(X, y)

# Save model
joblib.dump(model, "loan_model.pkl")

# Save encoders
joblib.dump(
    employment_encoder,
    "employment_encoder.pkl"
)

joblib.dump(
    eligible_encoder,
    "eligible_encoder.pkl"
)

print("Model Trained Successfully!")