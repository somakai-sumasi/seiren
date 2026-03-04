package domain

type FocusGroup string

const (
	FocusGroupBasic        FocusGroup = "basic"
	FocusGroupStructure    FocusGroup = "structure"
	FocusGroupQuality      FocusGroup = "quality"
	FocusGroupAntipatterns FocusGroup = "antipatterns"
	FocusGroupAll          FocusGroup = "all"
)

func (g FocusGroup) Focuses() []Focus {
	switch g {
	case FocusGroupBasic:
		return []Focus{
			FocusEncapsulation,
			FocusSeparationOfConcerns,
			FocusNaming,
		}
	case FocusGroupStructure:
		return []Focus{
			FocusDomainModel,
			FocusLayerSeparation,
			FocusLayerDataModel,
			FocusLayerRepository,
			FocusLayerService,
			FocusInterfaceDesign,
		}
	case FocusGroupQuality:
		return []Focus{
			FocusImmutability,
			FocusCohesion,
			FocusCoupling,
			FocusConditionalBranching,
		}
	case FocusGroupAntipatterns:
		return []Focus{
			FocusHalfBakedObject,
			FocusMethodChain,
			FocusDeadCode,
			FocusMagicNumber,
			FocusNullProblem,
			FocusExceptionAbuse,
			FocusGodClass,
		}
	case FocusGroupAll:
		return AllFocuses()
	default:
		return nil
	}
}

func FocusGroupFromString(value string) (FocusGroup, bool) {
	groups := []FocusGroup{
		FocusGroupBasic, FocusGroupStructure, FocusGroupQuality,
		FocusGroupAntipatterns, FocusGroupAll,
	}
	for _, g := range groups {
		if string(g) == value {
			return g, true
		}
	}
	return "", false
}
