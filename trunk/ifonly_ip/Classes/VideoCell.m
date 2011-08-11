#import "VideoCell.h"
#import "ChooseCategoryVC.h"

@implementation VideoCell

@synthesize dateLbl, titleLbl, durationLbl, imageCategory, entry;

- (id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier {
    
    self = [super initWithStyle:style reuseIdentifier:reuseIdentifier];
    if (self) {
		
    }
    return self;
}

- (id) initializeWithGData: (GDataEntryYouTubeVideo*) entry_ {
	self.entry = entry_;
	NSDateFormatter* dateFormatter = [[NSDateFormatter alloc] init];
	[dateFormatter setDateFormat:@"MMMM d, ''yy"];
	self.dateLbl.text = [dateFormatter stringFromDate: [[entry publishedDate] date]];
	self.titleLbl.text = [[entry title] stringValue];
	self.durationLbl.text = [NSString stringWithFormat:@"%i sec", [[[entry mediaGroup] duration] intValue]];
	ChooseCategoryVC* ccvc = [[[ChooseCategoryVC alloc] init] autorelease];
	NSString* imageName = @"";
	for (int i = 0; i < 6; i++) {
		NSString* categoryStr = [ccvc pickerView:nil titleForRow:i forComponent:0];
		if ( !([titleLbl.text rangeOfString:categoryStr].location == NSNotFound) ) {
			switch (i) {
				case 0:
					imageName = @"household.png"; break;
				case 1:
					imageName = @"garden.png"; break;
				case 2:
					imageName = @"tools.png"; break;
				case 3:
					imageName = @"electrical.png"; break;
				case 4:
					imageName = @"personal-products.png"; break;
				case 5:
					imageName = @"misc.png"; break;
			}
		}
	}
	self.imageCategory.image = [UIImage imageNamed:imageName];;
	return self;
}

- (void)setSelected:(BOOL)selected animated:(BOOL)animated {
    [super setSelected:selected animated:animated];
}

- (void)dealloc {
	[entry release];
	[imageCategory release];
	[dateLbl release];
	[titleLbl release];
	[durationLbl release];
    [super dealloc];
}

@end