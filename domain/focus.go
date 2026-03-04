package domain

type Focus string

const (
	// Core: Basic
	FocusEncapsulation        Focus = "encapsulation"
	FocusSeparationOfConcerns Focus = "separation-of-concerns"
	FocusNaming               Focus = "naming"

	// Core: Structure
	FocusDomainModel     Focus = "domain-model-completeness"
	FocusLayerSeparation Focus = "layer-separation"
	FocusLayerDataModel  Focus = "layer-data-model"
	FocusLayerRepository Focus = "layer-repository"
	FocusLayerService    Focus = "layer-service"
	FocusInterfaceDesign Focus = "interface-design"

	// Core: Quality
	FocusImmutability         Focus = "immutability"
	FocusCohesion             Focus = "cohesion"
	FocusCoupling             Focus = "coupling"
	FocusConditionalBranching Focus = "conditional-branching"

	// Core: Always included
	FocusDefectScoring Focus = "defect-scoring"

	// Antipatterns
	FocusHalfBakedObject Focus = "half-baked-object"
	FocusMethodChain     Focus = "method-chain"
	FocusDeadCode        Focus = "dead-code"
	FocusMagicNumber     Focus = "magic-number"
	FocusNullProblem     Focus = "null-problem"
	FocusExceptionAbuse  Focus = "exception-abuse"
	FocusGodClass        Focus = "god-class"
)

func AllFocuses() []Focus {
	return []Focus{
		FocusEncapsulation, FocusSeparationOfConcerns, FocusNaming,
		FocusDomainModel, FocusLayerSeparation, FocusLayerDataModel,
		FocusLayerRepository, FocusLayerService, FocusInterfaceDesign,
		FocusImmutability, FocusCohesion, FocusCoupling, FocusConditionalBranching,
		FocusDefectScoring,
		FocusHalfBakedObject, FocusMethodChain, FocusDeadCode,
		FocusMagicNumber, FocusNullProblem, FocusExceptionAbuse, FocusGodClass,
	}
}

func (f Focus) IsCore() bool {
	switch f {
	case FocusHalfBakedObject, FocusMethodChain, FocusDeadCode,
		FocusMagicNumber, FocusNullProblem, FocusExceptionAbuse, FocusGodClass:
		return false
	default:
		return true
	}
}

func (f Focus) PromptPath() string {
	if f.IsCore() {
		return "core/" + string(f)
	}
	return "antipatterns/" + string(f)
}

func FocusFromString(value string) (Focus, bool) {
	for _, f := range AllFocuses() {
		if string(f) == value {
			return f, true
		}
	}
	return "", false
}
