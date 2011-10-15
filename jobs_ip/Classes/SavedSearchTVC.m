#import "SavedSearchTVC.h"

@implementation SavedSearchTVC

@synthesize redDotCountLbl, titleLbl, locationSalaryLbl, blueCountLbl, frequencyLbl;

- (void)dealloc {
    [super dealloc];
	[redDotCountLbl release];
	[titleLbl release];
	[locationSalaryLbl release];
	[blueCountLbl release];
	[frequencyLbl release];
}

@end
