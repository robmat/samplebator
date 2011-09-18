#import "ChooseCvTC.h"

@implementation ChooseCvTC

@synthesize titleLbl, descLbl, dateLbl;

- (void)dealloc {
    [super dealloc];
	[titleLbl release];
	[descLbl release];
	[dateLbl release];
}

@end
