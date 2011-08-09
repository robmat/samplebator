#import "VideoCell.h"

@implementation VideoCell

@synthesize dateLbl, titleLbl, durationLbl;

- (id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier {
    
    self = [super initWithStyle:style reuseIdentifier:reuseIdentifier];
    if (self) {
		
    }
    return self;
}

- (void)setSelected:(BOOL)selected animated:(BOOL)animated {
    [super setSelected:selected animated:animated];
}

- (void)dealloc {
	[dateLbl release];
	[titleLbl release];
	[durationLbl release];
    [super dealloc];
}

@end